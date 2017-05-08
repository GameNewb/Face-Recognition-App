# User Login and Face Recognition App
---------------------
This is a basic web app that allows users to register and login to their appropriate profile. Once a user is logged in, users are able to upload videos through their localhost and onto the database.

The app is currently being developed using HTML/CSS, Javascript, PHP, MySQL, and Apache server on a Windows and Linux machine. Also, the app is dependent on the FFMPEG library as some functionalities require it.

---------------------
#### To run the program you must have the following installed on your system:

1. Apache server
2. MySQL
3. A text editor (e.g. Notepad++, SublimeText, Brackets, etc.) in case you want to make edits on the files
4. FFMPEG

Note: For ease of access, you can simply download XAMPP(Apache & MySQL) here: https://www.apachefriends.org/index.html

----------------------
#### Additional Dependencies:
- FFMPEG is included in this project repo. On Windows, you can simply add an environmental path to the ffmpeg folder and the dependencies will be fixed.
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
