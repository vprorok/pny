#!/usr/bin/python
import time
import sys
import os
import subprocess

def runJobs(pid1=None,pid2=None,pid3=None,pid4=None,pid5=None):
    if pid1 != None:
        killem1 = os.waitpid(pid1,0)
        killem2 = os.waitpid(pid2,0)
        killem3 = os.waitpid(pid3,0)
        killem4 = os.waitpid(pid4,0)
        killem4 = os.waitpid(pid5,0)
    pid1 = os.spawnl(os.P_NOWAIT, '/usr/bin/curl', 'curl', "-s","-o","/dev/null","http://www.swoopoclonedemo.com/daemons.php?type=bidbutler")
    pid2 = os.spawnl(os.P_NOWAIT, '/usr/bin/curl', 'curl', "-s","-o","/dev/null","http://www.swoopoclonedemo.com/daemons.php?type=extend")
    pid3 = os.spawnl(os.P_NOWAIT, '/usr/bin/curl', 'curl', "-s","-o","/dev/null","http://www.swoopoclonedemo.com/daemons.php?type=close")
    pid4 = os.spawnl(os.P_NOWAIT, '/usr/bin/curl', 'curl', "-s","-o","/dev/null","http://www.swoopoclonedemo.com/daemons/cleaner")
    pid5 = os.spawnl(os.P_NOWAIT, '/usr/bin/curl', 'curl', "-s","-o","/dev/null","http://www.swoopoclonedemo.com/daemons/winner")
    return pid1,pid2,pid3,pid4,pid5

numOfRuns = 0
waitTime = 60
pid1 = None
while(numOfRuns <= 58):
    time1 = time.time()
    if pid1 != None:
        pid1,pid2,pid3,pid4,pid5 = runJobs(pid1,pid2,pid3,pid4,pid5)
    else:
        pid1,pid2,pid3,pid4,pid5 = runJobs()
    numOfRuns += 1
    time2 = time.time()
    processingTime = time2 - time1
    sleepTime = waitTime - processingTime
    time.sleep(sleepTime)
