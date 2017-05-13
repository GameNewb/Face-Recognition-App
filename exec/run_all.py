#!/usr/bin/env python

#This program executes all other programs in correct order to obtain output video
#command format: ./run_all [name of video]
#---->  example: ./run_all test

import sys
import os

vidid = sys.argv[1]

os.system('./frame_split.py ' + vidid)
os.system('./getpoints.py ' + vidid)
os.system('./draw_triangles.py ' + vidid)
os.system('./frame_stitch.py ' + vidid)



