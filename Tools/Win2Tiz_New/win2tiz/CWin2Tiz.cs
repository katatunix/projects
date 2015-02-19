using System;
using System.Collections.Generic;
using System.Xml;
using System.IO;
using System.Threading;

using win2tiz.utils;
using win2tiz.visualc;

namespace win2tiz
{
	class CWin2Tiz
	{
		/// <summary>
		/// This method should be called ONCE after this object is created,
		/// because we cannot force stop it again (see the forceStop() method).
		/// </summary>
		/// <param name="args"></param>
		/// <returns></returns>
		public int main(string[] args)
		{
			printAbout();

			bool result = true;

			if (args.Length < 1 || args[0] == "-h")
			{
				printUsage();
			}
			else if (args[0] == "--server")
			{
				result = runServer(args);
			}
			else if (args[0] == "--batch")
			{
				result = runBatch(args);
			}
			else
			{
				result = runClient(args);
			}

			#region temp
			//Console.WriteLine();
			//Console.WriteLine("Press any key to exit...");
			//Console.ReadLine();
			#endregion

			m_waitHandle.Set();

			return result ? 0 : 1;
		}

		/// <summary>
		/// Force stop the main() method.
		/// Must be called ONCE because the program is killed after the first call,
		/// so the second call will never be excecuted or it will be interrupted in the middle.
		/// </summary>
		public void forceStop()
		{
			if (m_hasForceStop) return;
			m_hasForceStop = true;

			CConsole.writeWarning("Force stop, please wait...\n");

			// Stop client & batch
			lock (m_lockStopClient)
			{
				m_forceStopClient = true;
				if (m_builder != null)
				{
					m_builder.forceStop(); // this will make the call to m_builder.build() end
				}
			}

			// Stop server
			if (m_mongccServer != null)
			{
				m_mongccServer.forceStop();
			}

			// Block, wait until the main() totally stopped
			m_waitHandle.WaitOne();
		}

		//===========================================================================================
		
		private bool runServer(string[] args)
		{
			int port = s_kDefaultMongccPort;
			int backlog = s_kDefaultMongccBacklog;
			string tempDir = ".";

			for (int i = 1; i < args.Length; i++)
			{
				if (args[i] == "-port")
				{
					if (i + 1 >= args.Length) break;
					port = CXmlUtils.convertString2Int(args[i + 1]);
					i++;
				}
				else if (args[i] == "-backlog")
				{
					if (i + 1 >= args.Length) break;
					backlog = CXmlUtils.convertString2Int(args[i + 1]);
					i++;
				}
				else if (args[i] == "-tempdir")
				{
					if (i + 1 >= args.Length) break;
					tempDir = args[i + 1];
					i++;
				}
			}

			if (port <= 0) port = s_kDefaultMongccPort;
			if (backlog <= 0 || backlog > s_kDefaultMongccBacklog) backlog = s_kDefaultMongccBacklog;

			CConsole.writeInfo("Start mongcc server\n");

			m_mongccServer = new CMongccServer();
			m_mongccServer.start(port, backlog, tempDir);

			return true;
		}

		private bool runClient(string[] args)
		{
			#region temp
			//result = CWin2Tiz.make(
			//	//@"z:\Projects\IM3\externals\GllegacyConfig\_Android\sln2gcc.xml",
			//	//@"e:\Projects\OC\trunk\Android\GameSpecific\sln2gcc.xml",
			//	//@"z:\Projects\katatunix-projects\trunk\Tools\KataProfiler\Server\projects\android\Win2Tiz.xml",
			//	@"z:\Projects\A8_PUB\android\YAWOAP\native\Win2Tiz.xml",

			//	true,
			//	"armeabi-v7a",
			//	4,
			//	"all",
			//	false,
			//	//true,
			//	"release_armv7"
			//);
			#endregion

			#region Get input
			string inputFile = null;
			string typeOfBuild = "release";
			string projectToBuild = "all";
			string gccConfig = null;
			bool isVerbose = false;
			string outputFolderPath = "";
			
			int jobs = s_kDefaultJobs;

			for (int i = 0; i < args.Length; i++)
			{
				string arg = args[i];
				if (arg == "-i")
				{
					if (i + 1 >= args.Length) break;
					inputFile = args[i + 1];
					i++;
				}
				else if (arg == "-t")
				{
					if (i + 1 >= args.Length) break;
					if (args[i + 1] != "release" && args[i + 1] != "debug") break;
					typeOfBuild = args[i + 1];
					i++;
				}
				else if (arg == "-p")
				{
					if (i + 1 >= args.Length) break;
					projectToBuild = args[i + 1];
					i++;
				}
				else if (arg == "-g")
				{
					if (i + 1 >= args.Length) break;
					gccConfig = args[i + 1];
					i++;
				}
				else if (arg == "-v")
				{
					isVerbose = true;
				}
				else if (arg == "-j")
				{
					if (i + 1 >= args.Length) break;
					jobs = CXmlUtils.convertString2Int(args[i + 1]);
				}
				else if (arg == "-o")
				{
					if (i + 1 >= args.Length) break;
					outputFolderPath = args[i + 1];
					i++;
				}
			}

			if (inputFile == null)
			{
				CConsole.writeError("Error: The value for the option '-i' must be specified.\n");
				printUsage();
				return false;
			}
			#endregion

			return make(
				Path.GetFullPath(inputFile),
				typeOfBuild == "release",
				gccConfig,
				jobs,
				projectToBuild,
				isVerbose,
				outputFolderPath
			);
		}

		private bool runBatch(string[] args)
		{
			DateTime totalTimeBegin = DateTime.Now;

			int jobs = s_kDefaultJobs;
			string[] hosts = null;
			int port = s_kDefaultMongccPort;
			int needed = 0;
			int timeout = 0;
			int retry = 0;
			string file = null;
			bool verbose = false;

			for (int i = 1; i < args.Length; i++)
			{
				if (args[i] == "-jobs")
				{
					if (i + 1 >= args.Length) break;
					jobs = CXmlUtils.convertString2Int(args[i + 1]);
					i++;
				}
				else if (args[i] == "-hosts")
				{
					if (i + 1 >= args.Length) break;
					char[] separateChars = { ',' };
					hosts = args[i + 1].Split(separateChars, StringSplitOptions.RemoveEmptyEntries);
					i++;
				}
				else if (args[i] == "-port")
				{
					if (i + 1 >= args.Length) break;
					port = CXmlUtils.convertString2Int(args[i + 1]);
					i++;
				}
				else if (args[i] == "-needed")
				{
					if (i + 1 >= args.Length) break;
					needed = CXmlUtils.convertString2Int(args[i + 1]);
					i++;
				}
				else if (args[i] == "-timeout")
				{
					if (i + 1 >= args.Length) break;
					timeout = CXmlUtils.convertString2Int(args[i + 1]);
					i++;
				}
				else if (args[i] == "-retry")
				{
					if (i + 1 >= args.Length) break;
					retry = CXmlUtils.convertString2Int(args[i + 1]);
					i++;
				}
				else if (args[i] == "-file")
				{
					if (i + 1 >= args.Length) break;
					file = args[i + 1];
					i++;
				}
				else if (args[i] == "-verbose")
				{
					verbose = true;
				}
			}

			if (file == null)
			{
				CConsole.writeError("Error: The value for the option '-file' must be specified.\n");
				printUsage();
				return false;
			}

			file = Path.GetFullPath(file);

			if (!File.Exists(file))
			{
				CConsole.writeError("Error: Could not found the file " + file + ".\n");
				return false;
			}

			lock (m_lockStopClient)
			{
				m_builder = new CBuilder();
			}

			string workingDir = Path.GetDirectoryName(file);

			using (StreamReader reader = new StreamReader(file))
			{
				string cmd;
				while ((cmd = reader.ReadLine()) != null)
				{
					cmd = cmd.Trim();
					if (cmd.Length == 0 || cmd.StartsWith("//"))
					{
						continue;
					}

					string project = "Project";

					if (cmd[0] == '*')
					{
						int index = cmd.IndexOf(' ');
						if (index == -1)
						{
							CConsole.writeError("Error: Invalid compile command " + cmd + ".\n");
							return false;
						}
						project = cmd.Substring(1, index - 1);
						cmd = cmd.Substring(index + 1).Trim();
						if (cmd.Length == 0)
						{
							continue;
						}
					}

					// Parse the cmd
					string[] p = cmd.Split(s_kSeparateChars, StringSplitOptions.RemoveEmptyEntries);
					string sourceFileName = null;
					for (int i = 0; i < p.Length; i++)
					{
						if (p[i] == CUtils.s_kCompile)
						{
							if (i + 1 < p.Length)
							{
								sourceFileName = Path.GetFileName(p[i + 1]);
								i++;
								break;
							}
						}
					}

					if (sourceFileName == null)
					{
						CConsole.writeError("Error: Invalid compile command " + cmd + ".\n");
						return false;
					}

					string sourceFileExt = Path.GetExtension(sourceFileName);
					bool isAssembly = CUtils.isAssemblyExt(sourceFileExt);

					TCommand tCommand = new TCommand(cmd, cmd, workingDir,
						sourceFileName, ECommandType.eCompile, !isAssembly, project);
					m_builder.addCommand(tCommand);
				}
			}

			bool result = m_builder.build(verbose, jobs, hosts, port, needed, timeout, retry);

			m_builder = null;

			CConsole.writeTime("Total time: " + (DateTime.Now - totalTimeBegin).ToString() + "\n\n");

			return result;
		}

		/// <summary>
		/// </summary>
		/// <param name="configFilePath">Path to the XML config file (Win2Tiz.xml), can be absolute/relative path</param>
		/// <param name="isReleaseMode"></param>
		/// <param name="gccConfigName"></param>
		/// <param name="jobs"></param>
		/// <param name="projectNameToBuild">can be [null, "", "all"] to buil all projects</param>
		/// <param name="isVerbose"></param>
        /// <param name="outputFolderPath"></param>
		/// <returns></returns>
		private bool make(
			string	configFilePath,
			bool	isReleaseMode,
			string	gccConfigName,
			int		jobs,
			string	projectNameToBuild,
			bool	isVerbose,
            string	outputFolderPath)
		{
			CConsole.writeInfoLine("Config file: " + configFilePath);
			CConsole.writeInfoLine("Type of build: " + (isReleaseMode ? "release" : "debug"));
			CConsole.writeInfoLine("GCC config name: " + (string.IsNullOrEmpty(gccConfigName) ? "<default>" : gccConfigName));
			CConsole.writeInfoLine("Jobs: " + jobs);
			CConsole.writeInfoLine("Project to build: " + (string.IsNullOrEmpty(projectNameToBuild) ? "all" : projectNameToBuild));
			CConsole.writeInfoLine("Is verbose: " + (isVerbose ? "yes" : "no"));
			CConsole.writeInfoLine("Out folder: " + (string.IsNullOrEmpty(outputFolderPath) ? "<default>" : outputFolderPath));

			CConsole.writeLine();

			DateTime totalTimeBegin = DateTime.Now;
			bool result = true;

			XmlDocument win2TizXmlDoc;
			#region Load config file
			{
				CConsole.writeInfoLine("Load config file: " + configFilePath);
				win2TizXmlDoc = new XmlDocument();
				try
				{
					win2TizXmlDoc.Load(configFilePath);
				}
				catch (Exception)
				{
					CConsole.writeError("Error: could not load config file " + configFilePath + "\n");
					result = false;
					goto my_end;
				}
			}
			#endregion

			string slnWorkingDir;
			ASolution vcSolution;
			#region Load Visual C++ solution file
			{
				slnWorkingDir = Path.GetDirectoryName(configFilePath);
				XmlNodeList nodes = win2TizXmlDoc.GetElementsByTagName(KXml.s_kSolutionTag);
				if (nodes == null || nodes.Count <= 0)
				{
					CConsole.writeError("Error: tag " + KXml.s_kSolutionTag + " is not found\n");
					result = false;
					goto my_end;
				}

				XmlAttribute attr = nodes[0].Attributes[KXml.s_kSolutionPathAttr];
				if (attr == null)
				{
					CConsole.writeError("Error: attribute " + KXml.s_kSolutionPathAttr + " of tag " + KXml.s_kSolutionTag + " is not found\n");
					result = false;
					goto my_end;
				}

				vcSolution = CFactory.createSolution();

				string vcSlnFilePath = CUtils.combinePath(slnWorkingDir, attr.Value);
				CConsole.writeInfoLine("Load Visual C solution file: " + vcSlnFilePath);
				if (!vcSolution.load(vcSlnFilePath))
				{
					CConsole.writeError("Error: could not load Visual C++ solution file " + vcSlnFilePath + "\n");
					result = false;
					goto my_end;
				}
			}
			#endregion

			CGccConfig gccConfig;
			#region Load GccConfig node
			{
				XmlNodeList nodes = win2TizXmlDoc.GetElementsByTagName(KXml.s_kCommonGccConfigTag);
				if (nodes == null || nodes.Count <= 0)
				{
					CConsole.writeError("Error: tag " + KXml.s_kCommonGccConfigTag + " is not found\n");
					result = false;
					goto my_end;
				}

				XmlNode nodeGccConfig = null;
				foreach (XmlNode node in nodes[0].ChildNodes)
				{
					if (node.Name == KXml.s_kGccConfigTag && node.Attributes[KXml.s_kNameAttr] != null &&
						node.Attributes[KXml.s_kNameAttr].Value == gccConfigName)
					{
						nodeGccConfig = node;
						break;
					}
				}

				if (nodeGccConfig == null)
				{
					CConsole.writeWarning("Warning: this is an old-style config file, the <CommonGccConfig> node will be used as <GccConfig> node\n");
					nodeGccConfig = nodes[0];
					gccConfigName = null;
				}

				gccConfig = new CGccConfig(gccConfigName);
				if (!gccConfig.load(nodeGccConfig, isReleaseMode, slnWorkingDir))
				{
					// TODO
				}
			}
			CConsole.writeLine();
			#endregion

			string mainProjectName = gccConfig.get_MAIN_PROJECT(); // Can be null
			// Build only main project? It means buill all
			if (projectNameToBuild == mainProjectName) projectNameToBuild = null;
			bool onlyBuildOneProject =
				projectNameToBuild != null &&
				projectNameToBuild != "" &&
				projectNameToBuild != "all";

			List<XmlNode> projectNodesToBuild = new List<XmlNode>();

			#region Make projectNodesToBuild
			{
				XmlNodeList projectNodes = win2TizXmlDoc.GetElementsByTagName(KXml.s_kProjectTag);
				if (onlyBuildOneProject)
				{
					foreach (XmlNode projectNode in projectNodes)
					{
						XmlAttribute nameAttr = projectNode.Attributes[KXml.s_kNameAttr];
						if (nameAttr == null) continue;
						if (nameAttr.Value == projectNameToBuild)
						{
							projectNodesToBuild.Add(projectNode);
							break;
						}
					}
					if (projectNodesToBuild.Count == 0)
					{
						CConsole.writeError("Error: project " + projectNameToBuild + " is not found in the config file\n");
						result = false;
						goto my_end;
					}
				}
				else
				{
					XmlNode mainProjectNode = null;
					foreach (XmlNode projectNode in projectNodes)
					{
						XmlAttribute nameAttr = projectNode.Attributes[KXml.s_kNameAttr];
						if (nameAttr == null) continue;
						if (nameAttr.Value == mainProjectName)
						{
							if (mainProjectNode == null) mainProjectNode = projectNode;
						}
						else
						{
							projectNodesToBuild.Add(projectNode);
						}
					}
					// mainProjectNode can be null
					projectNodesToBuild.Add(mainProjectNode);
				}
			}
			#endregion

			//
			lock (m_lockStopClient)
			{
				m_builder = new CBuilder();
			}

			int projectCount = projectNodesToBuild.Count; // Sure projectCount >= 1
			List<TDepProjectInfo> depProjectInfos = new List<TDepProjectInfo>();
			bool isSomethingNewFromDepProjects = false;

			#region Loop through projectNodesToBuild to get list of commands to execute
			for (int i = 0; i < projectCount; i++)
			{
				XmlNode projectNode = projectNodesToBuild[i];
				if (projectNode == null) continue;

				string projectName = projectNode.Attributes[KXml.s_kNameAttr].Value;
				string projectNameSpec = projectName;

				CConsole.writeProject(projectName);

				AProject vcProject = vcSolution.getProject(projectName);
				if (vcProject == null)
				{
					if (onlyBuildOneProject)
					{
						CConsole.writeError("Error: project " + projectName + " is not found in the Visual C solution, it will be ignored\n\n");
						result = false;
						goto my_end;
					}

					CConsole.writeWarning("Warning: project " + projectName + " is not found in the Visual C solution, it will be ignored\n\n");
					continue;
				}

				#region Process S2G file
				{
					XmlAttribute useS2GFileAttr = projectNode.Attributes[KXml.s_kUseS2GFileTag];
					if (useS2GFileAttr != null)
					{
						XmlDocument s2gDoc = new XmlDocument();
						try
						{
							s2gDoc.Load(CUtils.combinePath(slnWorkingDir, useS2GFileAttr.Value));
							XmlNodeList nodes = s2gDoc.GetElementsByTagName(KXml.s_kProjectTag);
							if (nodes == null || nodes.Count <= 0)
							{
								CConsole.writeWarning("Warning: could not found tag " + KXml.s_kProjectTag + " in the S2G file " +
										useS2GFileAttr.Value + " for project " + projectName + ", it will not be used\n");
							}
							else
							{
								projectNode = nodes[0]; //===
							}
						}
						catch (Exception)
						{
							CConsole.writeWarning("Warning: could not found S2G file " +
								useS2GFileAttr.Value + " for project " + projectName + ", it will not be used\n");
						}
					}
				}
				#endregion

				bool isMainProject = !onlyBuildOneProject && i == projectCount - 1;
				EProjectType type = isMainProject ? EProjectType.eDynamicLib : EProjectType.eStaticLib;

				List<TCommand> projectCommands = CProject.load(
					gccConfig, projectNode, vcProject, isReleaseMode,
					type, depProjectInfos, isSomethingNewFromDepProjects,
					slnWorkingDir, outputFolderPath,
					out projectNameSpec);

				if (projectCommands == null)
				{
					CConsole.writeError("Error: something went wrong with project " + projectName + ", please double check\n\n");
					
					result = false;
					goto my_end;
				}

				CConsole.writeLine();

				if (isForceStopClient())
				{
					result = false;
					goto my_end;
				}

				//----------------------------------------------------------------------------------------
				m_builder.addCommands(projectCommands);
				//----------------------------------------------------------------------------------------

				if (!isMainProject)
				{
					depProjectInfos.Add(new TDepProjectInfo(projectName, projectNameSpec));
					if (projectCommands.Count > 0)
					{
						isSomethingNewFromDepProjects = true;
					}
				}
			}
			#endregion

			#region Mongcc
			if (gccConfig.getUseMongcc())
			{
				int port = gccConfig.getMongccPort();
				if (port <= 0) port = s_kDefaultMongccPort;
				result = m_builder.build(
					isVerbose, jobs,
					gccConfig.getMongccServers(),
					port,
					gccConfig.getMongccNeededServersNumber(),
					gccConfig.getMongccTimeout(),
					gccConfig.getMongccRetryConnectTime()
				);
			}
			else
			{
				CConsole.writeInfo("mongcc is disabled!\n\n");
				result = m_builder.build(isVerbose, jobs);
			}
			#endregion
			
			my_end:

			m_builder = null;

			CConsole.writeTime("Total time: " + (DateTime.Now - totalTimeBegin).ToString() + "\n\n");

			return result;
		}

		private bool isForceStopClient()
		{
			lock (m_lockStopClient)
			{
				return m_forceStopClient;
			}
		}

		//==============================================================================================
		
		private static void printUsage()
		{
			CConsole.setColor(EConsoleColor.eWhite);

			CConsole.writeLine("Client usage: Win2Tiz.exe -i <str> [-t <str>] [-p <str>] [-g <str>] [-v] [-j <num>]");

			CConsole.writeLine("  -i      input path/filename. (Ex: Win2Tiz.xml)");
			CConsole.writeLine("  -t      type of build <release|debug>");
			CConsole.writeLine("  -p      <project name> to build or <all>");
			CConsole.writeLine("  -g      the gcc config <GccConfig> </GccConfig> choosed from Win2Tiz.xml");
			CConsole.writeLine("  -v      verbose. Print a lot of info.");
			CConsole.writeLine("  -j      jobs or how many simultaneous processes.");
			CConsole.writeLine("  -o      output path name.");

			CConsole.writeLine("Server usage: Win2Tiz.exe --server [-port <port>] [-backlog <backlog>] [-tempdir <tempdir>]");
			CConsole.writeLine("  -port      port number to listen. Default: 1909.");
			CConsole.writeLine("  -backlog   max number of clients that server can handle at the same time. Default: 4.");
			CConsole.writeLine("  -tempdir   path to the folder that server can write temporary data. This should be short. Default: current.");
		}

		private static void printAbout()
		{
			CConsole.setColor(EConsoleColor.eWhite);
			CConsole.writeLine();
			CConsole.writeLine("Win2Tiz (c) nghia.buivan@gameloft.com, Summer 2014 - FIFA World Cup Brazil");
			CConsole.writeLine("(Since Spring 2013)");
			CConsole.writeLine();
		}

		//==============================================================================================
		
		private static readonly int s_kDefaultMongccPort = 1909;
		private static readonly int s_kDefaultMongccBacklog = 4;

		private static readonly int s_kDefaultJobs = 4;
		private static readonly char[] s_kSeparateChars = { ' ', '\t' };

		//==============================================================================================

		private CBuilder m_builder = null;

		private bool m_forceStopClient = false;
		private Object m_lockStopClient = new Object();

		private CMongccServer m_mongccServer = null;

		private bool m_hasForceStop = false;

		private AutoResetEvent m_waitHandle = new AutoResetEvent(false);
	}
}
