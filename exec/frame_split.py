#!/usr/bin/env python

#command format: ./frame_split [name of video to be split]
#---->  example: ./frame_split test

import subprocess
import sys
import os
import extract_metadata

vidid = sys.argv[1]
path_to_vid = sys.argv[2]
vid_save_location = sys.argv[3]

def extract_stills():
    cmd_split = ["ffmpeg", "-i", path_to_vid + vidid, "-f", "image2", vid_save_location + vidid + ".%d.png"]
    subprocess.call(cmd_split)

if __name__ == '__main__':
    print extract_stills()

