using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Diagnostics;
using System.Threading;

namespace Win2Tiz
{
    interface ICompileThreadObserver
    {
        void onCompleted(string fileName, string result, string fullCommand);
    }

	class CompileThread
	{
        private Thread m_thread = null;
        private ThreadStart m_threadStart = null;
        ICompileThreadObserver m_observer = null;

		private string m_fileName = null;
		private bool m_isRunning = false;
        private string m_command = null;
        private string m_workingDir = null;
        private int m_exitCode = 0;
        private string m_compileResult = null;

        private Object thisLock = new Object();

        public CompileThread(ICompileThreadObserver observer)
        {
            m_threadStart = new ThreadStart(this.compile);
            m_observer = observer;
        }

        public bool start()
        {
            m_isRunning = true;

            m_thread = new Thread(m_threadStart);
            m_thread.Start();
            
            return true;
        }

        private void compile()
        {
            ProcessStartInfo psi = new ProcessStartInfo();
            psi.UseShellExecute = false;
            psi.RedirectStandardError = true;
            psi.RedirectStandardOutput = true;
            psi.RedirectStandardInput = true;
            psi.WindowStyle = ProcessWindowStyle.Hidden;
            psi.CreateNoWindow = true;
            psi.ErrorDialog = false;
            psi.WorkingDirectory = m_workingDir;

            int k = this.Command.IndexOf(' ');
            psi.FileName = this.Command.Substring(0, k);
            psi.Arguments = this.Command.Substring(k + 1);

            m_compileResult = "";

            using (Process process = Process.Start(psi))
            using (ManualResetEvent mreOut = new ManualResetEvent(false), mreErr = new ManualResetEvent(false))
            {
                process.OutputDataReceived += (o, e) => { if (e.Data == null) mreOut.Set(); else output(e.Data); };
                process.BeginOutputReadLine();
                process.ErrorDataReceived += (o, e) => { if (e.Data == null) mreErr.Set(); else output(e.Data); };
                process.BeginErrorReadLine();

                process.StandardInput.Close();
                process.WaitForExit();

                mreOut.WaitOne();
                mreErr.WaitOne();

                m_exitCode = process.ExitCode;
            }

            if (m_observer != null) m_observer.onCompleted(m_fileName, m_compileResult, this.Command);

            m_isRunning = false;
        }

        private void output(string p)
        {
            m_compileResult += p + Environment.NewLine;
        }

        //=========================================================================================
        // Property
        //=========================================================================================
        public string FileName
        {
            get { return m_fileName; }
            set { m_fileName = value; }
        }

        public bool IsRunning
        {
            get { lock (thisLock) { return m_isRunning; } }
        }

        public string Command
        {
            get { return m_command; }
            set { m_command = value; }
        }

        public string WorkingDir
        {
            get { return m_workingDir; }
            set { m_workingDir = value; }
        }

        public int ExitCode
        {
            get { return m_exitCode; }
        }

        public string CompileResult
        {
            get { return m_compileResult; }
        }
        //=========================================================================================
        //=========================================================================================
	}
}
