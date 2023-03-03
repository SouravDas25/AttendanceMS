# AttendanceMS
Attendance Management System
* Web App + Android App
* Offline Attendance Facility in App ( attendance can be taken even when offline).

## Web App 

[//]: # (* Visit : https://ticteduattendence.000webhostapp.com + email : admin@admin.com + password : root)
* Laravel PHP
* MySQL

 
## Installation for Server

 - clone the repository.
 - cd AttendanceMS-Server
 - docker run --rm -v $(pwd):/app composer install
 - docker-compose up -d 
 - docker-compose exec web php artisan migrate
 - docker-compose exec web php artisan key:generate 
 - open http://localhost:8080/
 - done
 

## Installation for App

 - Open with Android Studio 2
   - use gradle 3.3
   - use android plugin 2.3.3



### Welcome Page
![Welcome](https://github.com/SouravDas25/AttendanceMS/blob/master/Minor-Project/Automated%20Attedence%20System/home.png)

### Dashboard Page
![Dashboard](https://github.com/SouravDas25/AttendanceMS/blob/master/Minor-Project/Automated%20Attedence%20System/dashboard.png)

### Take Attendance Page
![TakeAttendance](https://github.com/SouravDas25/AttendanceMS/blob/master/Minor-Project/Automated%20Attedence%20System/ta.png)

### View Attendance Overview Page
![ViewAttendance](https://github.com/SouravDas25/AttendanceMS/blob/master/Minor-Project/Automated%20Attedence%20System/va.png)

### View Attendance Page
![ViewAttendance](https://github.com/SouravDas25/AttendanceMS/blob/master/Minor-Project/Automated%20Attedence%20System/vad.png)




## Android App
* Java
* Sqlite

### Login
![Login](https://github.com/SouravDas25/AttendanceMS/blob/master/Minor-Project/Automated%20Attedence%20System/fceeaebc-a061-4398-9860-6827d2df9822.jpg)


### Home
![Home](https://github.com/SouravDas25/AttendanceMS/blob/master/Minor-Project/Automated%20Attedence%20System/5f3d32a6-504d-4bdc-9fc0-8d1e1e37f4d7.jpg)


### Dashboard
![Dashboard](https://github.com/SouravDas25/AttendanceMS/blob/master/Minor-Project/Automated%20Attedence%20System/d4174bf0-f171-4ad3-840e-a0781bab1811.jpg)

### Dashboard
![Dashboard](https://github.com/SouravDas25/AttendanceMS/blob/master/Minor-Project/Automated%20Attedence%20System/a52ee832-b8b1-4021-87f3-fa19eeab8715.jpg)

