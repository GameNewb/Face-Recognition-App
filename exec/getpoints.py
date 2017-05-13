#!/usr/bin/env python

#command format: ./getpoints [name of video to locate frames]
#---->  example: ./getpoints test

import sys
import subprocess
import os
import os.path
import string

vidid = sys.argv[1]
videopath = "videos/"
videofile = videopath + vidid + ".mp4"
imgdir = "frames/"

pointsdir = "points/"

if not os.path.isfile(videofile):
    print "error: video file " + videofile + " not found."
    sys.exit(1)

if not os.path.isdir(pointsdir):
    os.mkdir(pointsdir)

getcmd = "ffprobe -v 0 -select_streams v:0 -show_entries stream=nb_frames " \
         + videofile + "|grep -v STREAM|cut -f 2 -d="
nb_frames = subprocess.check_output(getcmd, shell=True)

fn = nb_frames.strip()
print "Please wait, processing " + fn + " frames"

for i in xrange(1, int(nb_frames) + 1):
    frameid = '{:d}'.format(i)
    imgfile = vidid + "." + frameid + ".png"
    if not os.path.isfile(imgdir + imgfile):
        print "err: frame file " + imgdir + imgfile + " not found."
        sys.exit(2)

    tfile = "/tmp/list_det_0.txt"
    
    getpointscmd = "FaceLandmarkImg -f " + imgfile + " -inroot " + imgdir + " -of list.txt -outroot /tmp"
    a = subprocess.check_output(getpointscmd, shell=True)
    
    catcmd = "cat " + tfile + "|grep -A 68 npoints" \
                + "|grep -v npoints|grep -v {|grep -v }"
    rawpoints = subprocess.check_output(catcmd, shell=True)

    # save points to file
    pointsfile = pointsdir + vidid + "." + frameid + ".points"
    filep = open(pointsfile, "w")

    for pline in rawpoints.splitlines():
        x, y = pline.split()
        outline = "%d %d\n" % (int(float(x)), int(float(y)))
        filep.write(outline)

    filep.close()

    print "Frame " + frameid + " complete"
