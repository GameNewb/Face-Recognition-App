# User Login and Face Recognition App
---------------------
This is a basic web app that allows users to register and login to their appropriate profile. Once a user is logged in, users are able to upload videos through their localhost and onto the database.

The app is currently being developed using HTML/CSS, Javascript, PHP, MySQL, and Apache server on a Windows and Linux machine. Also, the app is dependent on the FFMPEG library as some functionalities require it.

---------------------
#### To run the program you must have the following installed on your system:

1. Apache server **
2. MySQL **
3. A text editor (e.g. Notepad++, SublimeText, Brackets, etc.) in case you want to make edits on the files
4. FFMPEG
5. OpenFace
6. OpenCV
7. eyeLike (by Tristan Hume)

** : Must be running as an administrator

Note: 
- For ease of access, you can simply download XAMPP(Apache & MySQL) here: https://www.apachefriends.org/index.html
- For ffmpeg, download it here: https://ffmpeg.org/download.html

----------------------
#### Additional Dependencies:
- FFMPEG is required for this project to run properly. On Windows, you can simply add an environmental path to the ffmpeg folder and the dependencies will be fixed. On Linux, you must get the appropriate ffmpeg libraries and install it.
- This app uses an email verification feature. As such, you will need to modify your php.ini file as well as your sendmail.ini file (after installing XAMPP).
  + On Windows XAMPP
    - Go to XAMPP directory
    - XAMPP/php/php.ini (open with text editor)
    - XAMPP/sendmail/sendmail.ini (open with text editor)
    - Further instructions can be located here: http://stackoverflow.com/questions/15965376/how-to-configure-xampp-to-send-mail-from-localhost
  + On Linux
    - Install sendmail, mailutils, and ssmtp and configure the e-mail to use
    - Go to opt/lammp/etc and edit php.ini
      + Uncomment/remove ; from "extension=php_openssl.dll"
      + Set sendmail_path = "path/to/sendmail/ -t -i"
      + Further instructions can be located here: https://askubuntu.com/questions/47609/how-to-have-my-php-send-mail
- The entire repository folder permission must be changed to 0777 (or 0755), specially for "avatars" and "videos" folder
- If FFMPEG is not being executed, then chances are the library dependencies cannot be located by the program. One issue encountered was that libstdc++ couldn't be located.
  + The solution to this is to copy libstdc++ from the /usr/lib directory onto the /opt/lampp/lib/ directory
- Apache might also encounter some issues obtaining or creating some files. In order to fix this, permission must be changed for the entirety of the project folder.
  + Further instructions can be located here: http://stackoverflow.com/questions/5246114/php-mkdir-permission-denied-problem
- OpenFace's FaceLandmarkImg.cpp (located in OpenFace/exe/FaceLandmarkImg on your directory) must be replaced with the projects customized FaceLandmarkImg.cpp
  + OpenFace must then be re-built/re-installed for it to work
  + If issues occur (such as not obtaining the 68 data points), delete the current build file and re-build again
- eyeLike must be built/installed
  + Further instructions located inside the folder in this repo
