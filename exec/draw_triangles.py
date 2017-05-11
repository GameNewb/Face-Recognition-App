#!/usr/bin/env python

#Loop program that calls delaunay_triangles X number of time to cycle through all frames
#       X = the total number of frames in video
#command format: ./draw_triangles [name of video to locate frames]
#---->  example: ./draw_triangles test

import sys
import os

vidid = sys.argv[1]

for i in range(1, 26):
    os.system('./delaunay_triangles.py ' + vidid + ' {:d}'.format(i))


