#include <opencv2/objdetect/objdetect.hpp>
#include <opencv2/highgui/highgui.hpp>
#include <opencv2/imgproc/imgproc.hpp>

#include <iostream>
#include <queue>
#include <stdio.h>
#include <math.h>

#include "constants.h"
#include "findEyeCenter.h"
#include "findEyeCorner.h"


/** Constants **/


/** Function Headers */
void detectAndDisplay( cv::Mat frame );

/** Global variables x*/
//-- Note, either copy these two files from opencv/data/haarscascades to your current folder, or change these locations
cv::String face_cascade_name = "/opt/lampp/htdocs/Face-Recognition-App/modified_eyeLike/res/haarcascade_frontalface_alt.xml";
cv::CascadeClassifier face_cascade;
std::string main_window_name = "Capture - Face detection"; //video feed
std::string face_window_name = "Capture - Face"; // detected face
cv::RNG rng(12345);
cv::Mat debugImage;
cv::Mat skinCrCbHist = cv::Mat::zeros(cv::Size(256, 256), CV_8UC1);

/**
 * @function main
 */
int main( int argc, char* argv[] ) { //const char** argv
  cv::Mat frame;

  // Load the cascades
  if( !face_cascade.load( face_cascade_name ) ){ printf("--(!)Error loading face cascade, please change face_cascade_name in source code.\n"); return -1; };

  cv::namedWindow(main_window_name,CV_WINDOW_NORMAL);
  cv::moveWindow(main_window_name, 400, 100);
  cv::namedWindow(face_window_name,CV_WINDOW_NORMAL);
  cv::moveWindow(face_window_name, 10, 100);
  cv::namedWindow("Right Eye",CV_WINDOW_NORMAL);
  cv::moveWindow("Right Eye", 10, 600);
  cv::namedWindow("Left Eye",CV_WINDOW_NORMAL);
  cv::moveWindow("Left Eye", 400, 800); // 10
  //cv::namedWindow("aa",CV_WINDOW_NORMAL);
  //cv::moveWindow("aa", 10, 800);
  //cv::namedWindow("aaa",CV_WINDOW_NORMAL);
  //cv::moveWindow("aaa", 10, 800);

  createCornerKernels();
  ellipse(skinCrCbHist, cv::Point(113, 155.6), cv::Size(23.4, 15.2),
          43.0, 0.0, 360.0, cv::Scalar(255, 255, 255), -1);
  
  // edit begins --->> saira
  // loads a still image from argument --->> saira
  frame = cv::imread(argv[1], 1);
  
  // flip it --->> saira
  cv::flip(frame, frame, 1);
 
  // copy to debugImage --->> saira
  // dont know yet what this is for --->> saira
  frame.copyTo(debugImage);

  // apply the classifier --->> saira
  if( !frame.empty() ) {
        detectAndDisplay( frame );
      }
  else {
        printf("-- No image -- Program exits!\n");
        exit(-1);
  }

  /*show the image --->> saira
  imshow(main_window_name, debugImage);
  cv::waitKey(30);
  imwrite("output.png", debugImage);

  */ //edit ends --->> saira

  /* BEGIN : edited by saira, lets see if this will work
  // I make an attempt at supporting both 2.x and 3.x OpenCV
#if CV_MAJOR_VERSION < 3
  CvCapture* capture = cvCaptureFromCAM( -1 );
  if( capture ) {
    while( true ) {
      frame = cvQueryFrame( capture );
#else
  cv::VideoCapture capture(-1);
  if( capture.isOpened() ) {
    while( true ) {
      capture.read(frame);
#endif
      // mirror it
      cv::flip(frame, frame, 1);
      frame.copyTo(debugImage);

      // Apply the classifier to the frame
      if( !frame.empty() ) {
        detectAndDisplay( frame );
      }
      else {
        printf(" --(!) No captured frame -- Break!");
        break;
      }

      imshow(main_window_name,debugImage);

      int c = cv::waitKey(10);
      if( (char)c == 'c' ) { break; }
      if( (char)c == 'f' ) {
        imwrite("frame.png",frame);
      }

    }
  }
  */ //END

  releaseCornerKernels();

  return 0;
}

void findEyes(cv::Mat frame_gray, cv::Rect face) {
  cv::Mat faceROI = frame_gray(face);
  cv::Mat debugFace = faceROI;

  if (kSmoothFaceImage) {
    double sigma = kSmoothFaceFactor * face.width;
    GaussianBlur( faceROI, faceROI, cv::Size( 0, 0 ), sigma);
  }
  //-- Find eye regions and draw them
  int eye_region_width = face.width * (kEyePercentWidth/100.0);
  int eye_region_height = face.width * (kEyePercentHeight/100.0);
  int eye_region_top = face.height * (kEyePercentTop/100.0);
  cv::Rect leftEyeRegion(face.width*(kEyePercentSide/100.0),
                         eye_region_top,eye_region_width,eye_region_height);
  cv::Rect rightEyeRegion(face.width - eye_region_width - face.width*(kEyePercentSide/100.0),
                          eye_region_top,eye_region_width,eye_region_height);

  //-- Find Eye Centers
  cv::Point leftPupil = findEyeCenter(faceROI,leftEyeRegion,"Left Eye");
  cv::Point rightPupil = findEyeCenter(faceROI,rightEyeRegion,"Right Eye");
  // get corner regions
  cv::Rect leftRightCornerRegion(leftEyeRegion);
  leftRightCornerRegion.width -= leftPupil.x;
  leftRightCornerRegion.x += leftPupil.x;
  leftRightCornerRegion.height /= 2;
  leftRightCornerRegion.y += leftRightCornerRegion.height / 2;
  cv::Rect leftLeftCornerRegion(leftEyeRegion);
  leftLeftCornerRegion.width = leftPupil.x;
  leftLeftCornerRegion.height /= 2;
  leftLeftCornerRegion.y += leftLeftCornerRegion.height / 2;
  cv::Rect rightLeftCornerRegion(rightEyeRegion);
  rightLeftCornerRegion.width = rightPupil.x;
  rightLeftCornerRegion.height /= 2;
  rightLeftCornerRegion.y += rightLeftCornerRegion.height / 2;
  cv::Rect rightRightCornerRegion(rightEyeRegion);
  rightRightCornerRegion.width -= rightPupil.x;
  rightRightCornerRegion.x += rightPupil.x;
  rightRightCornerRegion.height /= 2;
  rightRightCornerRegion.y += rightRightCornerRegion.height / 2;
  rectangle(debugFace,leftRightCornerRegion,200);
  rectangle(debugFace,leftLeftCornerRegion,200);
  rectangle(debugFace,rightLeftCornerRegion,200);
  rectangle(debugFace,rightRightCornerRegion,200);
  // change eye centers to face coordinates
  rightPupil.x += rightEyeRegion.x;
  rightPupil.y += rightEyeRegion.y;
  leftPupil.x += leftEyeRegion.x;
  leftPupil.y += leftEyeRegion.y;

  // draw eye centers
  circle(debugFace, rightPupil, 3, 1234); 
  circle(debugFace, leftPupil, 3, 1234); 

  /* prints the coordinated of the pupils --->> saira
  
  printf("right pupil : %d, %d \n", rightPupil.x+face.x, rightPupil.y+face.y);
  printf("left pupil : %d, %d \n", leftPupil.x+face.x, leftPupil.y+face.y);
  */
  // BEGIN to get the coordinates of the pupils --->> saira
  rightPupil.x += face.x;
  rightPupil.y += face.y;
  leftPupil.x += face.x;
  leftPupil.y += face.y;
  std::cout << rightPupil << std::endl;
  std::cout << leftPupil << std::endl;

  //END --->> saira

  //-- Find Eye Corners
  if (kEnableEyeCorner) {
    cv::Point2f leftRightCorner = findEyeCorner(faceROI(leftRightCornerRegion), true, false);
    leftRightCorner.x += leftRightCornerRegion.x;
    leftRightCorner.y += leftRightCornerRegion.y;
    cv::Point2f leftLeftCorner = findEyeCorner(faceROI(leftLeftCornerRegion), true, true);
    leftLeftCorner.x += leftLeftCornerRegion.x;
    leftLeftCorner.y += leftLeftCornerRegion.y;
    cv::Point2f rightLeftCorner = findEyeCorner(faceROI(rightLeftCornerRegion), false, true);
    rightLeftCorner.x += rightLeftCornerRegion.x;
    rightLeftCorner.y += rightLeftCornerRegion.y;
    cv::Point2f rightRightCorner = findEyeCorner(faceROI(rightRightCornerRegion), false, false);
    rightRightCorner.x += rightRightCornerRegion.x;
    rightRightCorner.y += rightRightCornerRegion.y;
    circle(faceROI, leftRightCorner, 3, 200);
    circle(faceROI, leftLeftCorner, 3, 200);
    circle(faceROI, rightLeftCorner, 3, 200);
    circle(faceROI, rightRightCorner, 3, 200);
  }

  //imshow(face_window_name, faceROI); // --->> saira: remove "//" if needs to show the image
  
  // to display the image idefinitely --->> saira
  // cv::waitKey(6000); // --->> saira needs this to display the window above
  // end --->> saira
//  cv::Rect roi( cv::Point( 0, 0 ), faceROI.size());
//  cv::Mat destinationROI = debugImage( roi );
//  faceROI.copyTo( destinationROI );

//to show find eyes image --->> saira
//  imshow("find eyes output", destinationROI);
  // ends --->> saira
}


cv::Mat findSkin (cv::Mat &frame) {
  cv::Mat input;
  cv::Mat output = cv::Mat(frame.rows,frame.cols, CV_8U);

  cvtColor(frame, input, CV_BGR2YCrCb);

  for (int y = 0; y < input.rows; ++y) {
    const cv::Vec3b *Mr = input.ptr<cv::Vec3b>(y);
//    uchar *Or = output.ptr<uchar>(y);
    cv::Vec3b *Or = frame.ptr<cv::Vec3b>(y);
    for (int x = 0; x < input.cols; ++x) {
      cv::Vec3b ycrcb = Mr[x];
//      Or[x] = (skinCrCbHist.at<uchar>(ycrcb[1], ycrcb[2]) > 0) ? 255 : 0;
      if(skinCrCbHist.at<uchar>(ycrcb[1], ycrcb[2]) == 0) {
        Or[x] = cv::Vec3b(0,0,0);
      }
    }
  }
  return output;
}

/**
 * @function detectAndDisplay
 */
void detectAndDisplay( cv::Mat frame ) {
  std::vector<cv::Rect> faces;
  //cv::Mat frame_gray;

  std::vector<cv::Mat> rgbChannels(3);
  cv::split(frame, rgbChannels);
  cv::Mat frame_gray = rgbChannels[2];

  //cvtColor( frame, frame_gray, CV_BGR2GRAY );
  //equalizeHist( frame_gray, frame_gray );
  //cv::pow(frame_gray, CV_64F, frame_gray);
  //-- Detect faces
  face_cascade.detectMultiScale( frame_gray, faces, 1.1, 2, 0|CV_HAAR_SCALE_IMAGE|CV_HAAR_FIND_BIGGEST_OBJECT, cv::Size(150, 150) );
//  findSkin(debugImage);

  for( int i = 0; i < faces.size(); i++ )
  {
    // to know how many faces detected --->> saira
    //printf("Faces detected: %lu\n", faces.size());
    // end --->> saira
    rectangle(debugImage, faces[i], 1234);
  }
  //-- Show what you got
  if (faces.size() > 0) {
    // for debug --->> saira
    //printf("x of rectangle: %d \n", faces[0].x);
    //printf("y of rectangle: %d\n", faces[0].y);
    // end --->> saira
    findEyes(frame_gray, faces[0]);
  } else {
    // if no eyes detected --->> saira
    // printf("No eyes detected\n");
    // needs these output if no eyes detected, preferred output than error display --->> saira
    std::cout << cv::Point(-1, -1) << std::endl;
    std::cout << cv::Point(-1, -1) << std::endl;
    exit(-1);
  }
}
