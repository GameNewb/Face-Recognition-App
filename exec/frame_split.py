#!/usr/bin/env python

#command format: ./frame_split [name of video to be split]
#---->  example: ./frame_split test

import subprocess
import sys
import os
import extract_metadata
#"-f", "image2"

vidname = sys.argv[1]
vidid = sys.argv[2]
path_to_vid = sys.argv[3]
vid_save_location = sys.argv[4]

def extract_stills():
    cmd_split = ["ffmpeg", "-i", path_to_vid + vidname, "-r", "20", vid_save_location + vidid + ".%d.png"]
    subprocess.call(cmd_split)

if __name__ == '__main__':
    print extract_stills()

