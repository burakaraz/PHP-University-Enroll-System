create or replace PACKAGE Database
IS
  PROCEDURE login(
      p_user_id IN INTEGER,
      p_password   IN VARCHAR2,
      p_condition   IN VARCHAR2,
      p_RESULT OUT CHAR);
  PROCEDURE enrollAddCourse
    (
      p_userID   IN INTEGER,
      p_condition IN VARCHAR2,
      p_courseID  IN VARCHAR2,
      p_program  IN VARCHAR2,
      p_sectionNo IN INTEGER,
      p_RESULT OUT CHAR
    );
  PROCEDURE enrollUpdateSection
  (
    p_userID   IN INTEGER,
    p_condition IN VARCHAR2,
    p_courseID  IN VARCHAR2,
    p_sectionNo IN INTEGER,
    p_RESULT OUT CHAR
  );
   PROCEDURE enrollDropCourse
  (
      p_userID   IN INTEGER,
      p_condition IN VARCHAR2,
      p_courseID  IN VARCHAR2,
      p_sectionNo IN INTEGER
  );
  PROCEDURE getProfile
  (
    p_userID IN INTEGER,
    p_condition IN VARCHAR2,
    p_profile OUT SYS_REFCURSOR
  );
  PROCEDURE updateProfile
  (
    p_userID IN INTEGER,
    p_condition IN VARCHAR2,
    p_address IN VARCHAR2,
    p_phone IN VARCHAR2,
    p_email IN VARCHAR2,
    p_RESULT OUT CHAR);
  PROCEDURE getSemester
  (
    p_userID IN INTEGER,
    p_condition IN VARCHAR2,
    p_program IN VARCHAR2,
    p_profile OUT SYS_REFCURSOR
  );
  PROCEDURE getProgram
  (
    p_userID IN INTEGER,
    p_condition IN VARCHAR2,
    p_program IN VARCHAR2,
    p_profile OUT SYS_REFCURSOR
  );
  PROCEDURE gradeSummary
  (
    p_userID IN INTEGER,
    p_condition IN VARCHAR2,
    p_profile OUT SYS_REFCURSOR
  );
  PROCEDURE getGradeUpInfo
  (
    p_userID IN INTEGER,
    p_condition IN VARCHAR2,
    p_profile OUT SYS_REFCURSOR
  );
  PROCEDURE registration
  (
    p_userID IN INTEGER,
    p_condition IN VARCHAR2,
    p_profile OUT SYS_REFCURSOR
  );
  PROCEDURE getDepartment
  (
    p_profile OUT SYS_REFCURSOR
  );
  PROCEDURE getCourse
  (
    p_deptID IN VARCHAR2,
    p_profile OUT SYS_REFCURSOR
  );
  PROCEDURE getSection
  (
    p_courseName IN VARCHAR2,
    p_profile OUT SYS_REFCURSOR
  );
  PROCEDURE getSectionInfo
  (
    p_courseCode IN INTEGER,
    p_section IN INTEGER,
    p_profile OUT SYS_REFCURSOR
  );
  PROCEDURE loginStaff(
      p_user_id IN INTEGER,
      p_password   IN VARCHAR2,
      p_RESULT OUT CHAR);
   PROCEDURE getStaffProfile
  (
    p_userID IN INTEGER,
    p_profile OUT SYS_REFCURSOR
  );
  PROCEDURE updateStaffProfile
  (
    p_userID IN INTEGER,
    p_address IN VARCHAR2,
    p_phone IN VARCHAR2,
    p_email IN VARCHAR2,
    p_RESULT OUT CHAR
  );
  PROCEDURE loginProf(
      p_user_id IN INTEGER,
      p_password   IN VARCHAR2,
      p_RESULT OUT CHAR);
  PROCEDURE getProfProfile
  (
    p_userID IN INTEGER,
    p_profile OUT SYS_REFCURSOR
  );
  PROCEDURE updateProfProfile
  (
    p_userID IN INTEGER,
    p_address IN VARCHAR2,
    p_phone IN VARCHAR2,
    p_email IN VARCHAR2,
    p_RESULT OUT CHAR
  );
  PROCEDURE getMySections
  (
    p_empID IN INTEGER,
    p_profile OUT SYS_REFCURSOR
  );    
   PROCEDURE getMySectionsUp
  (
    p_empID IN INTEGER,
    p_profile OUT SYS_REFCURSOR
  );
   PROCEDURE getMyStudentList
  (
    p_courseID IN INTEGER,
    p_secNo IN INTEGER,
    p_profile OUT SYS_REFCURSOR
  );   
   PROCEDURE getMyStudents
  (
    p_empID IN INTEGER,
    p_profile OUT SYS_REFCURSOR
  );
  PROCEDURE loginAdmin(
      p_user_id IN INTEGER,
      p_password   IN VARCHAR2,
      p_RESULT OUT CHAR);
  PROCEDURE showUnder(
       p_profile OUT SYS_REFCURSOR);
  
  PROCEDURE addUnder(
      p_stuId    IN INTEGER,
      p_deptId   IN INTEGER,
      p_name     IN VARCHAR2,
      p_surname  IN VARCHAR2,
      p_natId    IN INTEGER,
      p_email    IN VARCHAR2,
      p_phone    IN INTEGER,
      p_address  IN VARCHAR2,
      p_password IN INTEGER,
      p_cgpa     IN FLOAT,
      p_RESULT OUT CHAR);
      
  PROCEDURE showGrad(
       p_profile OUT SYS_REFCURSOR);
       
  PROCEDURE addGrad(
      p_stuId    IN INTEGER,
      p_deptId   IN INTEGER,
      p_name     IN VARCHAR2,
      p_surname  IN VARCHAR2,
      p_natId    IN INTEGER,
      p_email    IN VARCHAR2,
      p_phone    IN INTEGER,
      p_address  IN VARCHAR2,
      p_password IN INTEGER,
      p_cgpa     IN FLOAT,  
      p_pre IN VARCHAR2,
      p_RESULT OUT CHAR);
      
  PROCEDURE showSpec(
       p_profile OUT SYS_REFCURSOR);
       
  PROCEDURE addSpec(
      p_stuId    IN INTEGER,
      p_deptId   IN INTEGER,
      p_name     IN VARCHAR2,
      p_surname  IN VARCHAR2,
      p_natId    IN INTEGER,
      p_email    IN VARCHAR2,
      p_phone    IN INTEGER,
      p_address  IN VARCHAR2,
      p_password IN INTEGER,
      p_pre IN VARCHAR2,
      p_RESULT OUT CHAR);
      
      
  PROCEDURE showProf(
       p_profile OUT SYS_REFCURSOR);
       
  PROCEDURE addProf(
      p_stuId    IN INTEGER,
      p_deptId   IN INTEGER,
      p_name     IN VARCHAR2,
      p_surname  IN VARCHAR2,
      p_natId    IN INTEGER,
      p_salary   IN INTEGER,
      p_email    IN VARCHAR2, 
      p_phone    IN INTEGER,
      p_address  IN VARCHAR2,
      p_password IN INTEGER,
      p_title IN VARCHAR2,
      p_office IN VARCHAR2,
      p_pre IN VARCHAR2,
      p_RESULT OUT CHAR);
      
  PROCEDURE showStaff(
       p_profile OUT SYS_REFCURSOR);
       
  PROCEDURE addStaff(
      p_stuId    IN INTEGER,
      p_deptId   IN INTEGER,
      p_name     IN VARCHAR2,
      p_surname  IN VARCHAR2,
      p_natId    IN INTEGER,
      p_salary   IN INTEGER,
      p_email    IN VARCHAR2, 
      p_phone    IN INTEGER,
      p_address  IN VARCHAR2,
      p_password IN INTEGER,
      p_res IN VARCHAR2,
      p_RESULT OUT CHAR);
  
  PROCEDURE showCourse(
       p_profile OUT SYS_REFCURSOR);
       
  PROCEDURE addCourse(
      p_cid   IN INTEGER,
      p_did   IN INTEGER,
      p_name     IN VARCHAR2,
      p_credit  IN VARCHAR2,
      p_RESULT OUT CHAR);
      
  PROCEDURE showSection(
       p_profile OUT SYS_REFCURSOR);
       
  PROCEDURE addSection(
      p_sec   IN INTEGER,
      p_cid   IN INTEGER,
      p_cap    IN INTEGER,
      p_RESULT OUT CHAR);
      
  PROCEDURE showSectionInfo(
       p_profile OUT SYS_REFCURSOR);
       
  PROCEDURE addSectionInfo(
      p_cid   IN INTEGER,
      p_sid   IN INTEGER,
      p_dateSec    IN VARCHAR2,
      p_loc    IN VARCHAR2,
      p_start    IN VARCHAR2,
      p_end    IN VARCHAR2,
      p_RESULT OUT CHAR);
    
   PROCEDURE showStudent(
       p_profile OUT SYS_REFCURSOR); 
       
  PROCEDURE addAdvise(
      p_pid   IN INTEGER,
      p_sid   IN INTEGER,
      p_con   IN INTEGER,
      p_RESULT OUT CHAR);
      
  PROCEDURE showUnderReg(
       p_profile OUT SYS_REFCURSOR); 
  
  PROCEDURE showGradReg(
       p_profile OUT SYS_REFCURSOR); 
  
  PROCEDURE showSpecReg(
       p_profile OUT SYS_REFCURSOR); 
       
  PROCEDURE showProg(
       p_profile OUT SYS_REFCURSOR); 
       
   PROCEDURE addReg(
      p_pid   IN INTEGER,
      p_sid   IN INTEGER,
      p_did   IN INTEGER,
      P_flag IN INTEGER,
      p_RESULT OUT CHAR);
      
  PROCEDURE showNotSection(
       p_profile OUT SYS_REFCURSOR); 
       
  PROCEDURE addCourseProf(
      p_cid   IN INTEGER,
      p_sid   IN INTEGER,
      p_pid   IN INTEGER,
      p_RESULT OUT CHAR);
      
END Database;