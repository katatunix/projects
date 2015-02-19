using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Xml;
using System.Text.RegularExpressions;
using System.IO;

using Microsoft.VisualStudio.VCProjectEngine;
using System.Diagnostics;
using System.Threading;

namespace Win2Tiz
{
	class Win2Tiz : ICompileThreadObserver
	{
		private const int MAX_THREAD_NUMBER = 8;

		private static string[] SEPARATE = { ";", " ", "\t", "\r\n" };
		private static string[] SEPARATE_VS = { ";" };
		private static string FOLDER_RELEASE = "release";
		private static string FOLDER_DEBUG = "debug";

		private Dictionary<string, string> m_macrosDict = new Dictionary<string, string>();
		XmlDocument m_win2TizXmlDoc = null;

		Regex m_projectRegex = new Regex(@"Project\(""\{(.*)\}""\) = ""(.*)"", ""(.*)"", ""\{(.*)\}""");
		Dictionary<string, string> m_projectsDict = new Dictionary<string, string>();

		VCProjectEngine m_vcprojEngine = new VCProjectEngineObject();
		VCProject m_vcProject;
		string m_projectPath;

		string m_defines;
		string m_cflags;
		string m_includes;

		bool m_isReleaseMode;
		List<IgnoreItem> m_ignoresList;
		List<SpecificCFLAGS> m_specificCFLAGSList;

		private CompileThread[] m_threads = new CompileThread[MAX_THREAD_NUMBER];
		private string m_workingDir;
		private string m_tempCompileDir;

		private string[] m_listTypeToBeCompiled;

		List<string> m_objFiles = new List<string>();
		List<string> m_commands = new List<string>();
		List<string> m_sourceFiles = new List<string>();

		bool m_compileSuccess;
		int m_indexCompile;
		int m_countCompile;

		private bool m_isVerbose;
		private int m_jobs;
		private string m_gccConfig;
		private XmlNode m_nodeGccConfig;
		private bool m_isRelinkDynamic;
		private List<string> m_listStaticProjectNames = new List<string>();


		private Object thisLock = new Object();

		public Win2Tiz()
		{
			for (int i = 0; i < MAX_THREAD_NUMBER; i++)
			{
				m_threads[i] = new CompileThread(this);
			}
		}

		#region ICompileThreadObserver Members

		public void onCompleted(string fileName, string result, string fullCommand)
		{
			lock (thisLock)
			{
				if (!m_compileSuccess) return;

				Console.WriteLine();
				MyConsole.writeCommand(string.Format("{0}/{1}. Compile: {2} ", ++m_indexCompile, m_countCompile, fileName));
				if (m_isVerbose)
				{
					Console.WriteLine();
					MyConsole.writeLineNormal(fullCommand);
				}

				string objFile = m_tempCompileDir + "\\" + Path.GetFileNameWithoutExtension(fileName) + ".o";
				if (checkAndPrintResult(result))
				{
					m_objFiles.Add(objFile.Replace('\\', '/'));
				}
				else
				{
					try { File.Delete(objFile); }
					catch (Exception) { }
					m_compileSuccess = false;
				}
			}
		}

		#endregion

		public void process(	string	win2TizXmlFile,
								string	projectToBuild,
								bool	isReleaseMode,
								string	gccConfig,
								bool	isVerbose,
								int		jobsNum)
		{
			DateTime globalDt_Begin = DateTime.Now;

			m_isReleaseMode = isReleaseMode;
			m_jobs = jobsNum;
			if (m_jobs > MAX_THREAD_NUMBER) m_jobs = MAX_THREAD_NUMBER;
			m_isVerbose = isVerbose;
			m_gccConfig = gccConfig;
			m_nodeGccConfig = null;

			#region Load solution

			// Reset all
			m_macrosDict.Clear();
			m_projectsDict.Clear();
			m_objFiles.Clear();

			// Load win2TizXmlFile
			m_win2TizXmlDoc = new XmlDocument();
			try
			{
				m_win2TizXmlDoc.Load(win2TizXmlFile);
			}
			catch (Exception)
			{
				Console.WriteLine("Error: the input file is not exsited.");
				return;
			}

			m_workingDir = Path.GetDirectoryName(win2TizXmlFile);

			String solutionPath = PathUtils.combinePath(
				m_workingDir,
				m_win2TizXmlDoc.GetElementsByTagName("Solution")[0].Attributes["Path"].Value.Replace('/', '\\')
			);

			// Load the .sln file
			StreamReader reader = new StreamReader(solutionPath);
			string line;
			Match matchProjInfo;
			while ((line = reader.ReadLine()) != null)
			{
				matchProjInfo = m_projectRegex.Match(line);
				if (matchProjInfo.Success)
				{
					string solutionFolder = Path.GetDirectoryName(solutionPath);
					m_projectsDict.Add(matchProjInfo.Groups[2].Value,
						PathUtils.combinePath(solutionFolder, matchProjInfo.Groups[3].Value)
					);
				}
			}
			reader.Close();

			#endregion

			m_listTypeToBeCompiled = getMacroValue("TYPES_OF_FILES_TO_BE_COMPILED").Split(';');

			#region Load and compile projects
			m_compileSuccess = true;
			m_isRelinkDynamic = false;

			XmlNode nodeProjectMain = null;
			m_listStaticProjectNames.Clear();

			foreach (XmlNode _nodeProject in m_win2TizXmlDoc.GetElementsByTagName("Project"))
			{
				XmlNode nodeProject = _nodeProject;
				
				string projectName = _nodeProject.Attributes["Name"].Value;

				bool isMainProject = getMacroValue("MAIN_PROJECT") == projectName;
				if (!isMainProject)
				{
					m_listStaticProjectNames.Add(projectName);
				}

				if (projectToBuild != "all" && projectToBuild != projectName) continue; // Ignore this project

				if (_nodeProject.Attributes["UseS2GFile"] != null)
				{
					string s2gFile = _nodeProject.Attributes["UseS2GFile"].Value;
					
					String s2gPath = PathUtils.combinePath(
						m_workingDir,
						s2gFile.Replace('/', '\\')
					);
					XmlDocument s2gDoc = new XmlDocument();
					try
					{
						s2gDoc.Load(s2gPath);
						nodeProject = s2gDoc.GetElementsByTagName("Project")[0];
					}
					catch (Exception)
					{
						nodeProject = _nodeProject;
					}
				}

				if (nodeProjectMain == null && isMainProject)
				{
					nodeProjectMain = nodeProject;
				}
				else if (!compileProject(nodeProject, projectName))
				{
					break;
				}
			}

			if (m_compileSuccess && nodeProjectMain != null)
			{
				if (!compileProject(nodeProjectMain, getMacroValue("MAIN_PROJECT")))
				{
				}
			}
			#endregion

			DateTime globalDt_End = DateTime.Now;
			Console.WriteLine();
			MyConsole.writeLineTime("Total time: " + (globalDt_End - globalDt_Begin));
		}

		private bool compileProject(XmlNode nodeProject, string projectName)
		{
			try
			{
				m_projectPath = Path.GetDirectoryName(m_projectsDict[projectName]);
			}
			catch (Exception)
			{
				Console.WriteLine();
				MyConsole.writeLineWarning("WARNING: could not found project [" + projectName + "] -> Ignore it!!!");
				return true;
			}

			MyConsole.writeProject(projectName);

			MyConsole.writeLineCommand("Preparing...");

			

			string modeString = m_isReleaseMode ? FOLDER_RELEASE : FOLDER_DEBUG;
			string modeFolder = m_workingDir + "\\" + modeString;
			m_tempCompileDir = modeString + "\\" + projectName;


			try { Directory.CreateDirectory(modeFolder + '\\'); }
			catch (Exception) { };
			try { Directory.CreateDirectory(modeFolder + '\\' + projectName + '\\'); }
			catch (Exception) { };
			
			
			

			// Load the .vcproj file
			m_vcProject = (VCProject)m_vcprojEngine.LoadProject(m_projectsDict[projectName]);

			// DEFINES
			//string s_DEFINES = getLocalMacroFromXml("DEFINES", nodeProject);
			//m_defines = makeDefines(getMacroValue("DEFINES")) + " " + makeDefines(s_DEFINES);
			m_defines = makeDefines(getLocalMacroFromXml("DEFINES", nodeProject, true));

			// CFLAGS
			//string s_CFLAGS = getLocalMacroFromXml("CFLAGS", nodeProject);
			//m_cflags = getMacroValue("CFLAGS") + " " + s_CFLAGS;
			m_cflags = getLocalMacroFromXml("CFLAGS", nodeProject, true);

			// INCLUDE_PATHS
			#region
			m_macrosDict.Add("ProjectDir", m_projectPath + "\\");
			//string s_INCLUDE_PATHS = espaceMacro(getLocalMacroFromXml("INCLUDE_PATHS", nodeProject));
			//m_includes = makeIncludePath(getMacroValue("INCLUDE_PATHS")) + " " + makeIncludePath(s_INCLUDE_PATHS);
			m_includes = makeIncludePath(espaceMacro(getLocalMacroFromXml("INCLUDE_PATHS", nodeProject, true)));

			string tmp = getLocalMacroFromXml("USE_ADDITIONAL_INCLUDE_DIRECTORIES_FROM_VS", nodeProject, true);
			
			if (string.IsNullOrEmpty(tmp) || tmp.Equals("true", StringComparison.CurrentCultureIgnoreCase))
			{
				string win32Config = getMSVCConfiguration(nodeProject);

				IVCCollection configCollection = (IVCCollection)m_vcProject.Configurations;
				VCConfiguration selectedConfig = null;
				foreach (VCConfiguration config in configCollection)
				{
					if (config.Name == win32Config || config.ConfigurationName == win32Config)
					{
						selectedConfig = config;
						break;
					}
				}

				if (selectedConfig != null)
				{
					m_includes += " " + makeVSIncludePathFromTools((IVCCollection)selectedConfig.Tools);
					//foreach (VCPropertySheet ps in (IVCCollection)selectedConfig.PropertySheets)
					//{
					//    m_includes += " " + makeVSIncludePath(ps);
					//}
				}
			}

			m_macrosDict.Remove("ProjectDir");

			#endregion

			DateTime d1 = DateTime.Now;
			#region Calculate the list files to be compiled
			m_ignoresList = getIgnoresList(nodeProject);
			m_specificCFLAGSList = getSpecificList(nodeProject);

			m_commands.Clear();
			m_objFiles.Clear();
			m_sourceFiles.Clear();

			foreach (VCFile file in (IVCCollection)m_vcProject.Files)
			{
				makeCompileCommand(PathUtils.combinePath(m_projectPath, file.RelativePath).Trim());
			}

			//foreach (VCFilter filter in (IVCCollection)m_vcProject.Files)
			//{
			//    //filter.
			//}

			foreach (XmlNode node in nodeProject.ChildNodes) if (node.Name == "AddSourceFileToProject")
				{
					foreach (XmlNode nodeFile in node.ChildNodes) if (nodeFile.NodeType != XmlNodeType.Comment)
						{
							makeCompileCommand(PathUtils.combinePath(m_projectPath, nodeFile.Attributes["Name"].Value).Trim());
						}
				}
			#endregion

			#region Compile
			m_compileSuccess = true;
			m_countCompile = m_sourceFiles.Count;
			m_indexCompile = 0;

			for (int i = 0; i < m_countCompile; i++)
			{
				lock (thisLock)
				{
					if (!m_compileSuccess) break;
				}
				int freeSlot = waitForFreeThread();
				m_threads[freeSlot].FileName = m_sourceFiles[i];
				m_threads[freeSlot].Command = m_commands[i];
				m_threads[freeSlot].WorkingDir = m_workingDir;

				if (!m_threads[freeSlot].start())
				{
					MyConsole.writeLineError("ERROR: can not start compiling " + m_sourceFiles[i]);
					lock (thisLock) { m_compileSuccess = false; }
					break;
				}
			}

			waitUntilAllThreadsFinish();

			if (!m_compileSuccess) return false;

			#endregion

			#region Link

			if (getMacroValue("MAIN_PROJECT") == projectName)
			{
				bool hasNewDynamic = false;

				string dsymName = "lib" + projectName + ".dsym";
				string dsymName_Full = m_workingDir + "/" + modeString + "/" + projectName + "/" + dsymName;
				bool xmlConfigGenDsym = getMacroValue("GENERATE_DSYM").Equals("true", StringComparison.CurrentCultureIgnoreCase);
				bool existDsym = File.Exists(dsymName_Full);
				bool needDsym = xmlConfigGenDsym && !existDsym;

				#region Dynamic Link
				string outName = "lib" + projectName + ".so";
				string outName_Full = m_workingDir + "/" + modeString + "/" + projectName + "/" + outName;
				if (m_isRelinkDynamic || !File.Exists(outName_Full) || needDsym)
				{
					hasNewDynamic = true;

					string OBJ_FILES = "";
					foreach (string obj in m_objFiles)
					{
						OBJ_FILES += obj + " ";
					}
					
					string backup_LINK_PATHS = m_macrosDict.ContainsKey("LINK_PATHS") ? m_macrosDict["LINK_PATHS"] : null;
					string backup_LDLIBS = m_macrosDict.ContainsKey("LDLIBS") ? m_macrosDict["LDLIBS"] : null;
					string backup_LDFLAGS = m_macrosDict.ContainsKey("LDFLAGS") ? m_macrosDict["LDFLAGS"] : null;

					string LINK_PATHS = makeLinkPath(getLocalMacroFromXml("LINK_PATHS", nodeProject, true));
					foreach (string staticProjectName in m_listStaticProjectNames)
					{
						LINK_PATHS += " -L" + modeString + "\\" + staticProjectName;
					}

					m_macrosDict["LINK_PATHS"] = LINK_PATHS;
					m_macrosDict["LDLIBS"] = getLocalMacroFromXml("LDLIBS", nodeProject, false);
					m_macrosDict["LDFLAGS"] = getLocalMacroFromXml("LDFLAGS", nodeProject, true);

					m_macrosDict.Add("OBJ_FILES", OBJ_FILES);
					m_macrosDict.Add("OUT", modeString + "/" + outName);

					string command = getMacroValue("DYNAMIC_LINK_COMMAND_LINE").Replace("\r\n", " ").Replace("\t", " ").Replace(";", " ");

					m_macrosDict.Remove("OBJ_FILES");
					m_macrosDict.Remove("OUT");

					if (backup_LINK_PATHS == null) m_macrosDict.Remove("LINK_PATHS");
					else m_macrosDict["LINK_PATHS"] = backup_LINK_PATHS;
					if (backup_LDLIBS == null) m_macrosDict.Remove("LDLIBS");
					else m_macrosDict["LDLIBS"] = backup_LDLIBS;
					if (backup_LDFLAGS == null) m_macrosDict.Remove("LDFLAGS");
					else m_macrosDict["LDFLAGS"] = backup_LDFLAGS;
					

					Console.WriteLine();
					MyConsole.writeCommand("Link: " + outName + " ");
					if (m_isVerbose)
					{
						Console.WriteLine();
						MyConsole.writeLineNormal(command);
					}
					if (!startProcess(command))
					{
						return false;
					}
				}
				else
				{
					Console.WriteLine();
					MyConsole.writeLineCommand("Link already: " + outName);
				}

				#endregion

				#region Gen DSYM
				if (xmlConfigGenDsym && (hasNewDynamic || !existDsym))
				{
					m_macrosDict.Add("INPUT", modeString + "/" + outName);
					m_macrosDict.Add("OUT", modeString + "/" + dsymName);
					string command_DSYM = getMacroValue("DSYM_COMMAND_LINE").Replace("\r\n", " ").Replace("\t", " ").Replace(";", " ");
					m_macrosDict.Remove("OUT");
					m_macrosDict.Remove("INPUT");

					Console.WriteLine();
					MyConsole.writeCommand("Generate DSYM: " + dsymName + " ");

					if (m_isVerbose)
					{
						Console.WriteLine();
						MyConsole.writeLineNormal(command_DSYM);
					}
					if (!startProcess(command_DSYM))
					{
						return false;
					}
				}
				else
				{
					Console.WriteLine();
					MyConsole.writeLineCommand("Generate DSYM already: " + dsymName);
				}
				#endregion

				#region Strip
				if (hasNewDynamic)
				{
					m_macrosDict.Add("INPUT", modeString + "/" + outName);
					string command_Strip = getMacroValue("STRIP_COMMAND_LINE").Replace("\r\n", " ").Replace("\t", " ").Replace(";", " ");
					m_macrosDict.Remove("INPUT");

					Console.WriteLine();
					MyConsole.writeCommand("Strip: " + outName + " ");

					if (m_isVerbose)
					{
						Console.WriteLine();
						MyConsole.writeLineNormal(command_Strip);
					}
					if (!startProcess(command_Strip))
					{
						return false;
					}
				}
				else
				{
					Console.WriteLine();
					MyConsole.writeCommand("Strip already: " + outName);
				}

				#endregion
			}
			else
			{
				#region Static Link

				string aFile = modeString + "/" + projectName + "/lib" + projectName + ".a";
				string aFile_Full = m_workingDir + "/" + aFile;

				if (m_countCompile == 0 && File.Exists(aFile_Full))
				{
					Console.WriteLine();
					MyConsole.writeCommand("Link already: lib" + projectName + ".a");
				}
				else
				{
					m_isRelinkDynamic = true;

					try
					{
						File.Delete(aFile_Full);
					}
					catch (Exception ex)
					{
						MyConsole.writeLineError("ERROR: " + ex.Message);
					}

					string OBJ_FILES = "";
					foreach (string obj in m_objFiles)
					{
						OBJ_FILES += obj + " ";
					}

					m_macrosDict.Add("OUT", aFile);
					m_macrosDict.Add("OBJ_FILES", OBJ_FILES);

					string command = getMacroValue("STATIC_LINK_COMMAND_LINE");

					int spaceIndex = command.IndexOf(' ');
					string tmpFile = Path.GetTempFileName();
					
					StreamWriter writer = new StreamWriter(tmpFile);
					writer.Write(command.Substring(spaceIndex));
					writer.Close();

					Console.WriteLine();
					MyConsole.writeCommand("Link: lib" + projectName + ".a ");

					if (m_isVerbose)
					{
						Console.WriteLine();
						MyConsole.writeLineNormal(command);
					}

					m_macrosDict.Remove("OUT");
					m_macrosDict.Remove("OBJ_FILES");

					string cheatCommand = command.Substring(0, spaceIndex) + " @" + tmpFile;

					if (!startProcess(cheatCommand))
					{
						return false;
					}

					File.Delete(tmpFile);
				}

				#endregion
			}

			#endregion

			DateTime d2 = DateTime.Now;
			Console.WriteLine();
			Console.WriteLine();
			MyConsole.writeLineTime("Time: " + (d2 - d1));

			return true;
		}

		private string getMacroValue(string name)
		{
			string value = null;
			if (m_macrosDict.ContainsKey(name))
			{
				value = m_macrosDict[name];
			}
			else
			{
				value = getGlobalMacroFromXml(name);
				if (value == null) value = getGlobalMacroFromEnv(name);
			}

			//if (value == null) return "$(" + name + ")";
			if (value == null) return "";

			return espaceMacro(value);
		}

		private string espaceMacro(string value)
		{
			if (value == null) return "";
			string result = "";
			for (int i = 0; i < value.Length; i++)
			{
				if (value[i] == '$' && i + 1 < value.Length && value[i + 1] == '(')
				{
					for (int j = i + 2; j < value.Length; j++)
					{
						if (value[j] == ')')
						{
							string macro = value.Substring(i + 2, j - i - 2);
							result += getMacroValue(macro);

							i = j;
							break;
						}
					}
				}
				else
				{
					result += value[i];
				}
			}

			if (result.IndexOf("..\\") > -1 || result.IndexOf("../") > -1)
			{
				string fp = null;
				try
				{
					fp = Path.GetFullPath(result);
				}
				catch (Exception)
				{
					fp = null;
				}
				if (fp != null) result = fp;
			}

			return result;
		}

		private string getGlobalMacroFromXml(string name)
		{
			if (m_nodeGccConfig == null)
			{
				XmlNode nodeCommonGccConfig = m_win2TizXmlDoc.GetElementsByTagName("CommonGccConfig")[0];

				if (m_gccConfig == null)
				{
					m_nodeGccConfig = nodeCommonGccConfig.ChildNodes[0];
				}
				else
				{
					foreach (XmlNode node in nodeCommonGccConfig.ChildNodes)
					{
						if (node.Name == "GccConfig" && node.Attributes["Name"].Value == m_gccConfig)
						{
							m_nodeGccConfig = node;
							break;
						}
					}
				}
			}

			if (m_nodeGccConfig == null) return null;

			foreach (XmlNode node in m_nodeGccConfig.ChildNodes)
			{
				if (node.Name == "Macro" &&
					node.Attributes["Name"] != null &&
					node.Attributes["Name"].Value == name)
				{
					return getMacroTagValue(node);
				}
			}
			return null;
		}

		private string getGlobalMacroFromEnv(string name)
		{
			string value = Environment.GetEnvironmentVariable(name, EnvironmentVariableTarget.User);
			if (value != null) return value;

			value = Environment.GetEnvironmentVariable(name, EnvironmentVariableTarget.Process);
			if (value != null) return value;

			return Environment.GetEnvironmentVariable(name, EnvironmentVariableTarget.Machine);
		}

		private bool isValidExtension(string path)
		{
			string ext = Path.GetExtension(path);
			if (ext.Length > 0 && ext[0] == '.') ext = ext.Substring(1);
			foreach (string e in m_listTypeToBeCompiled)
			{
				if (e == ext) return true;
			}
			return false;
		}

		private string getLocalMacroFromXml(string name, XmlNode nodeProject, bool isGlobalFirst)
		{
			string globalValue = getMacroValue(name);

			foreach (XmlNode node in nodeProject.ChildNodes)
			{
				if (node.Name == "Macro" &&
					node.Attributes["Name"] != null &&
					node.Attributes["Name"].Value == name)
				{
					string addValue = getMacroTagValue(node);
					string removeValue = getMacroTagValue_Remove(node);
					string[] arr;

					if (!string.IsNullOrEmpty(removeValue))
					{
						arr = removeValue.Split(SEPARATE, StringSplitOptions.RemoveEmptyEntries);
						foreach (string flag in arr)
						{
							globalValue = globalValue.Replace(flag, "");
						}
					}
					
					arr = addValue.Split(SEPARATE, StringSplitOptions.RemoveEmptyEntries);
					string tmp = "";
					foreach (string flag in arr)
					{
						tmp += flag + " ";
					}

					if (isGlobalFirst) return (globalValue + " " + tmp).Trim();

					return (tmp + globalValue).Trim();
				}
			}
			return globalValue.Trim();
		}

		private string getMacroTagValue(XmlNode node)
		{
			string value = "";
			if (node.Attributes["Value"] != null)
				value += node.Attributes["Value"].Value + " ";
			if (node.Attributes["CommonValue"] != null)
				value += node.Attributes["CommonValue"].Value + " ";

			if (m_isReleaseMode && node.Attributes["ReleaseValue"] != null)
				value += node.Attributes["ReleaseValue"].Value + " ";
			else if (!m_isReleaseMode && node.Attributes["DebugValue"] != null)
				value += node.Attributes["DebugValue"].Value + " ";
			return value.Trim();
		}

		private string getMacroTagValue_Remove(XmlNode node)
		{
			if (m_isReleaseMode && node.Attributes["ReleaseValue_Remove"] != null)
			{
				return node.Attributes["ReleaseValue_Remove"].Value;
			}
			if (!m_isReleaseMode && node.Attributes["DebugValue_Remove"] != null)
			{
				return node.Attributes["DebugValue_Remove"].Value;
			}
			return null;
		}

		private string getMSVCConfiguration(XmlNode nodeProject)
		{
			foreach (XmlNode node in nodeProject.ChildNodes)
			{
				if (node.Name == "MSVCConfiguration")
				{
					return m_isReleaseMode ? node.Attributes["Release"].Value : node.Attributes["Debug"].Value;
				}
			}
			return m_isReleaseMode ? "Release" : "Debug";
		}

		private List<IgnoreItem> getIgnoresList(XmlNode nodeProject)
		{
			List<IgnoreItem> list = new List<IgnoreItem>();
			foreach (XmlNode node in nodeProject.ChildNodes)
			{
				if (node.Name == "Ignore")
				{
					foreach (XmlNode node2 in node.ChildNodes) if (node2.NodeType != XmlNodeType.Comment)
					{
						IgnoreItem item = new IgnoreItem();
						item.m_type = node2.Name == "Filter" ? IgnoreItemType.FILTER : IgnoreItemType.FILE;
						item.m_name = node2.Attributes["Name"].Value.Replace('/', '\\');
						list.Add(item);
					}
				}
			}
			return list;
		}

		private List<SpecificCFLAGS> getSpecificList(XmlNode nodeProject)
		{
			List<SpecificCFLAGS> list = new List<SpecificCFLAGS>();
			foreach (XmlNode node in nodeProject.ChildNodes)
			{
				if (node.Name != "FileSpecific") continue;
				
				foreach (XmlNode node2 in node.ChildNodes)
				{
					if (node2.Name != "File") continue;

					if (node.Attributes["Name"] == null || string.IsNullOrEmpty(node.Attributes["Name"].Value)) continue;
				
					SpecificCFLAGS sp = new SpecificCFLAGS();
					sp.m_File = node.Attributes["Name"].Value;
					if (node.Attributes["CFLAGS"] != null)
					{
						sp.m_RemoveValue = "*";
						sp.m_AddValue = node.Attributes["CFLAGS"].Value;
					}
					else
					{
						sp.m_RemoveValue = node.Attributes["RemoveValue"].Value;
						sp.m_AddValue = node.Attributes["AddValue"].Value;
					}

					list.Add(sp);
				}
			
			}
			return list;
		}

		private string makeDefines(string defines)
		{
			string[] p = defines.Split(SEPARATE, StringSplitOptions.RemoveEmptyEntries);
			string result = "";
			foreach (String macro in p)
			{
				result += "-D" + macro + " ";
			}
			return result;
		}

		private string makeIncludePath(string includePath)
		{
			if (includePath == null) return "";
			string[] p = includePath.Split(SEPARATE_VS, StringSplitOptions.RemoveEmptyEntries);
			includePath = "";
			foreach (string p2 in p)
			{
				if (p2.IndexOf("Microsoft Visual Studio") == -1 &&
					p2.IndexOf("Microsoft SDKs") == -1)
				{
					includePath += p2 + ";";
				}
			}

			p = includePath.Split(SEPARATE, StringSplitOptions.RemoveEmptyEntries);
			string result = "";
			foreach (String path in p)
			{
				string t = path.Replace('/', '\\').Replace('\"', ' ').Trim();
				t = PathUtils.removeEndSlashes(t);
				if (!Path.IsPathRooted(t))
				{
					t = PathUtils.combinePath(m_projectPath, t);
				}
				t = Path.GetFullPath(t);
				t = PathUtils.removeEndSlashes(t);
				result += "-I" + t.Trim() + " ";
			}
			return result;
		}

		private string makeLinkPath(string linkPath)
		{
			if (linkPath == null) return "";

			string[] p = linkPath.Split(SEPARATE, StringSplitOptions.RemoveEmptyEntries);
			string result = "";
			foreach (String path in p)
			{
				string t = path.Replace('/', '\\').Replace('\"', ' ').Trim();
				t = PathUtils.removeEndSlashes(t);
				if (!Path.IsPathRooted(t))
				{
					t = PathUtils.combinePath(m_workingDir, t);
				}
				t = Path.GetFullPath(t);
				t = PathUtils.removeEndSlashes(t);
				result += "-L" + t.Trim() + " ";
			}
			return result;
		}

		private string makeVSIncludePath(VCPropertySheet sheet)
		{
			string result = makeVSIncludePathFromTools((IVCCollection)sheet.Tools);

			IVCCollection propertySheetsCollection = (IVCCollection)sheet.PropertySheets;
			foreach (VCPropertySheet ps in propertySheetsCollection)
			{
				result += " " + makeVSIncludePath(ps);
			}

			return result;
		}

		private string makeVSIncludePathFromTools(IVCCollection vcTools)
		{
			VCCLCompilerTool compTool = (VCCLCompilerTool)vcTools.Item("VCCLCompilerTool");
			//return makeIncludePath(espaceMacro(compTool.AdditionalIncludeDirectories));
			return compTool == null ? "" : makeIncludePath(compTool.FullIncludePath);
		}

		private bool startProcess(string command) // Sync process, for linker
		{
			Process p = new Process();
			p.StartInfo.CreateNoWindow = true;
			p.StartInfo.UseShellExecute = false;
			p.StartInfo.RedirectStandardOutput = true;
			p.StartInfo.RedirectStandardError = true;
			p.StartInfo.WorkingDirectory = m_workingDir;

			int k = command.IndexOf(' ');
			p.StartInfo.FileName = command.Substring(0, k);
			p.StartInfo.Arguments = command.Substring(k + 1);

			try
			{
				p.Start();
				string result = p.StandardError.ReadToEnd().Trim();
				result += Environment.NewLine + p.StandardOutput.ReadToEnd().Trim();
				checkAndPrintResult(result);
				return true;
			}
			catch (Exception ex)
			{
				MyConsole.writeLineError("ERROR when execute command: " + command);
				MyConsole.writeLineError("ERROR message: " + ex.Message);
				return false;
			}
		}

		private void makeCompileCommand(string filePath)
		{
			filePath = filePath.Replace('/', '\\');
			string fileName = Path.GetFileName(filePath);
			string baseName = Path.GetFileNameWithoutExtension(fileName);
			string filePathWithoutExt = Path.GetDirectoryName(filePath) + "\\" + baseName;
			string ext = Path.GetExtension(fileName);
			string objFile = m_tempCompileDir + "\\" + baseName + ".o";
			string depFile = m_tempCompileDir + "\\" + baseName + ".d";

			if (!isValidExtension(fileName)) return;

			foreach (IgnoreItem item in m_ignoresList)
			{
				if (item.m_type == IgnoreItemType.FILE)
				{
					string f = item.m_name;
					if (f.IndexOf('\\') == -1)
					{
						if (f == baseName || f == fileName) return;
					}
					else
					{
						string full = PathUtils.combinePath(m_projectPath, f).Trim();
						bool hasExt = Path.GetExtension(full).Length != 0;

						if (hasExt && filePath.Equals(full, StringComparison.CurrentCultureIgnoreCase))
						{
							return;
						}
						if (!hasExt && filePathWithoutExt.Equals(full, StringComparison.CurrentCultureIgnoreCase))
						{
							return;
						}
					}
				}
				else // FILTER
				{
					foreach (VCFilter filter in (IVCCollection)m_vcProject.Filters)
					{
						if (isIgnoreFilter(filter, filePath, item.m_name)) return;
					}
				}
			}

			#region Check re-compile
			bool isReCompile = false;
			string objFileAbs = PathUtils.combinePath(m_workingDir, objFile);
			string depFileAbs = PathUtils.combinePath(m_workingDir, depFile);

			if (!File.Exists(objFileAbs))
			{
				isReCompile = true;
			}
			else
			{
				DateTime dtObjFile = File.GetLastWriteTime(PathUtils.combinePath(m_workingDir, objFile));

				if (File.GetLastWriteTime(filePath) >= dtObjFile)
				{
					isReCompile = true;
				}
				else if (File.Exists(depFileAbs))
				{
					List<string> depList = PathUtils.getDependFilesList(depFileAbs);
					if (depList != null)
					{
						foreach (string dep in depList)
						{
							if (File.GetLastWriteTime(dep) >= dtObjFile)
							{
								isReCompile = true;
								break;
							}
						}
					}
				}
				else
				{
					isReCompile = true;
				}
			}

			
			//isReCompile = true;// cheat

			if (!isReCompile)
			{
				m_objFiles.Add(objFile.Replace('\\', '/'));
				return;
			}
			#endregion

			string cflags = m_cflags;

			foreach (SpecificCFLAGS sp in m_specificCFLAGSList)
			{
				if (sp.m_File == baseName)
				{
					string[] arr;

					if (sp.m_RemoveValue == "*")
					{
						cflags = "";
					}
					else
					{
						arr = sp.m_RemoveValue.Split(SEPARATE, StringSplitOptions.RemoveEmptyEntries);
						foreach (string flag in arr)
						{
							cflags = cflags.Replace(flag, "");
						}
					}

					arr = sp.m_AddValue.Split(SEPARATE, StringSplitOptions.RemoveEmptyEntries);
					foreach (string flag in arr)
					{
						cflags += " " + flag;
					}

					break;
				}
			}

			string backup_DEFINE = m_macrosDict.ContainsKey("DEFINES") ? m_macrosDict["DEFINES"] : null;
			string backup_CFLAGS = m_macrosDict.ContainsKey("CFLAGS") ? m_macrosDict["CFLAGS"] : null;
			string backup_INCLUDE_PATHS = m_macrosDict.ContainsKey("INCLUDE_PATHS") ? m_macrosDict["INCLUDE_PATHS"] : null;
			
			m_macrosDict["DEFINES"] = m_defines;
			m_macrosDict["CFLAGS"] = cflags;
			m_macrosDict["INCLUDE_PATHS"] = m_includes;

			m_macrosDict["OBJ_FILE"] = objFile;// TODO, check file name is existed: rename fileName2
			m_macrosDict["SRC_FILE"] = filePath;

			string t;
			if (ext == ".cpp" ||
				ext == ".cc" ||
				ext == ".cxx" ||
				ext == "C")
				t = getMacroValue("COMPILE_CPP_COMMAND_LINE");
			else
				t = getMacroValue("COMPILE_CC_COMMAND_LINE");

			string command = t.Replace("\r\n", " ").Replace("\t", " ").Replace(";", " ") +
													" -MMD -MP -MF" + depFile + " -MT" + depFile;

			if (backup_DEFINE == null) m_macrosDict.Remove("DEFINES");
			else m_macrosDict["DEFINES"] = backup_DEFINE;
			if (backup_CFLAGS == null) m_macrosDict.Remove("CFLAGS");
			else m_macrosDict["CFLAGS"] = backup_CFLAGS;
			if (backup_INCLUDE_PATHS == null) m_macrosDict.Remove("INCLUDE_PATHS");
			else m_macrosDict["INCLUDE_PATHS"] = backup_INCLUDE_PATHS;

			m_macrosDict.Remove("OBJ_FILE");
			m_macrosDict.Remove("SRC_FILE");

			m_commands.Add(command);
			m_sourceFiles.Add(fileName);
		}

		private bool isIgnoreFilter(VCFilter filter, string filePath, string ignoreName)
		{
			if (filter.Name == ignoreName)
			{
				foreach (VCFile fileInFilter in (IVCCollection)filter.Files)
				{
					string fileInFilterPath = PathUtils.combinePath(m_projectPath, fileInFilter.RelativePath);
					if (fileInFilterPath.Equals(filePath, StringComparison.CurrentCultureIgnoreCase))
					{
						return true;
					}
				}
			}

			foreach (VCFilter subFilter in (IVCCollection)filter.Filters)
			{
				if (isIgnoreFilter(subFilter, filePath, ignoreName)) return true;
			}

			return false;
		}

		private int waitForFreeThread()
		{
			while (true)
			{
				for (int i = 0; i < m_jobs; i++)
				{
					if (!m_threads[i].IsRunning)
						return i;
				}
				Thread.Sleep(100);
			}
		}

		private void waitUntilAllThreadsFinish()
		{
			while (true)
			{
				bool finish = true;
				for (int i = 0; i < m_jobs; i++)
				{
					if (m_threads[i].IsRunning)
					{
						finish = false;
						break;
					}
				}
				if (finish) return;
				Thread.Sleep(200);
			}
		}

		private bool checkAndPrintResult(string result)
		{
			result = result.Trim();

			if (result.IndexOf("error:", StringComparison.CurrentCultureIgnoreCase) != -1 ||
                result.IndexOf("undefined reference to", StringComparison.CurrentCultureIgnoreCase) != -1)
			{
				MyConsole.writeLineError("(error)");
				MyConsole.writeLineResult(result);
				return false;
			}
			if (result.IndexOf("warning:", StringComparison.CurrentCultureIgnoreCase) != -1)
			{
				MyConsole.writeLineWarning("(success with warning)");
				MyConsole.writeLineResult(result);
				return true;
			}

			MyConsole.writeLineSuccess("(success)");
			
			if (result.Length > 0)
			{
				MyConsole.writeLineResult(result);
			}

			return true;
		}

	}
}
