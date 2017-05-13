#!/usr/bin/env python

#command format: ./extract_metadata [name of video]
#---->  example: ./extract_metadata test

import subprocess
import sys

vidid = sys.argv[1]


def find_width(video):
    cmd_res = ["ffprobe", "-v", "error", "-of", "default=noprint_wrappers=1:nokey=1", 
                "-select_streams", "v:0", "-show_entries", "stream=width", "videos/" + video + ".mp4"]
    out_width = subprocess.check_output(cmd_res)
    return out_width

def find_height(video):
    cmd_res = ["ffprobe", "-v", "error", "-of", "default=noprint_wrappers=1:nokey=1", 
                "-select_streams", "v:0", "-show_entries", "stream=height", "videos/" + video + ".mp4"]
    out_height = subprocess.check_output(cmd_res)
    return out_height


def find_num_frames(video):
    cmd_num = ["ffprobe", "-v", "error", "-of", "default=noprint_wrappers=1:nokey=1", 
               "-count_frames", "-select_streams", "v:0", "-show_entries",
               "stream=nb_read_frames", "videos/" + video + ".mp4"]

    out_num = subprocess.check_output(cmd_num)
    return out_num


def find_fps(video):
    cmd_fps = ["ffprobe", "-v", "error", "-of", "default=noprint_wrappers=1:nokey=1",
                "-select_streams", "v:0", "-show_entries", "stream=r_frame_rate", video]

    out_fps = subprocess.check_output(cmd_fps)
    #split1 = out_fps.split("/")
    return out_fps

if __name__ == '__main__':
    print "Width: " + find_width(vidid)
    print "Height: " + find_height(vidid)
    print "Number of frames: " + find_num_frames(vidid)
    print "Frames per second: " + find_fps(vidid)
