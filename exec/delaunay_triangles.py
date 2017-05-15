#!/usr/bin/env python

#command format: ./delaunay_triangles [name of video] [number of the frame to be drawn]
#---->  example: ./delaunay_triangles test 1

import sys, json
import subprocess
import os
import os.path
import string
import cv2
import numpy as np
import random

try:
    data = json.loads(sys.argv[1])
except:
    print("Error loading json data")
    sys.exit(1)

vidid = data['videoID']
frameNum = data['frameID']

videopath = "videos/"
videofile = videopath + vidid + ".mp4"
imgdir = "frames/"

frameid = '{:d}'.format(int(frameNum))
imgfile = imgdir + vidid + "." + frameid + ".png"
outfile = imgdir + vidid + "." + frameid + ".tri.png"

pointsdir = "points/"
pointsfile = pointsdir + vidid + "." + frameid + ".points"

if not os.path.isfile(videofile):
    print "error: video file " + videofile + " not found."
    sys.exit(1)

if not os.path.isdir(imgdir):
    print "error: frames directory " + imgdir + " not found."
    sys.exit(1)

if not os.path.isdir(pointsdir):
    print "error: points directory " + pointsdir + " not found."
    sys.exit(1)

if not os.path.isfile(imgfile):
    print "error: video frame " + imgfile + " not found."
    sys.exit(1)

if not os.path.isfile(pointsfile):
    print "error: video frame " + pointsfile + " not found."
    sys.exit(1)


# Check if a point is inside a rectangle
def rect_contains(rect, point) :
    if point[0] < rect[0] :
        return False
    elif point[1] < rect[1] :
        return False
    elif point[0] > rect[2] :
        return False
    elif point[1] > rect[3] :
        return False
    return True
 
# Draw a point
def draw_point(img, p, color ) :
    cv2.circle (img, p, 3, color, cv2.FILLED, cv2.LINE_8, 0)
 
 
# Draw delaunay triangles
def draw_delaunay(img, subdiv, delaunay_color ) :
 
    triangleList = subdiv.getTriangleList();
    size = img.shape
    r = (0, 0, size[1], size[0])
 
    for t in triangleList :
        pt1 = (t[0], t[1])
        pt2 = (t[2], t[3])
        pt3 = (t[4], t[5])
        if rect_contains(r, pt1) and rect_contains(r, pt2) and rect_contains(r, pt3) :
            cv2.line(img, pt1, pt2, delaunay_color, 1, cv2.LINE_8, 0)
            cv2.line(img, pt2, pt3, delaunay_color, 1, cv2.LINE_8, 0)
            cv2.line(img, pt3, pt1, delaunay_color, 1, cv2.LINE_8, 0)
 
if __name__ == '__main__':
 
    # Define window names
    win_delaunay = "Delaunay Triangulation"
 
    # Turn on animation while drawing triangles
    animate = True
     
    # Define colors for drawing.
    delaunay_color = (255,0,0)
    points_color = (0, 0, 255)
 
    # Read in the image.
    img = cv2.imread(imgfile);
     
    # Rectangle to be used with Subdiv2D
    size = img.shape
    rect = (0, 0, size[1], size[0])
     
    # Create an instance of Subdiv2D
    subdiv = cv2.Subdiv2D(rect);
 
    # Create an array of points.
    points = [data[['facialPoints'][str(frameID)]]];

    #with open(pointsfile, "r") as filep:
      #  spoints = filep.read()

    #for pline in spoints.splitlines():
     #   x, y = pline.split()

        #print "pline is " + pline
        #print "x is " + x + " and y is " + y

      #  points.append((int(x), int(y)))
        # points.append((float(x), float(y)))
 
    # Insert points into subdiv
    for p in points :
        subdiv.insert(p)
         
    #print "ready to draw ..."

    # Draw delaunay triangles
    draw_delaunay (img, subdiv, (255, 0, 0));

    #print "now draw points"
 
    # Draw points
    for p in points :
        print "Drawing points"
        draw_point(img, p, (0,0,255))

    cv2.imwrite(outfile, img);


