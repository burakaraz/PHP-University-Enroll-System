create or replace PACKAGE BODY Database
AS
  PROCEDURE login(
      p_user_id IN INTEGER,
      p_password   IN VARCHAR2,
      p_condition   IN VARCHAR2,
      p_RESULT OUT CHAR)
  IS
    CheckLogin INTEGER;
  BEGIN
    if(p_condition = 'Undergraduate') then
      SELECT COUNT(*) INTO CheckLogin FROM UnderGraduate 
      WHERE studentID = p_user_id and password = p_password;         
      IF CheckLogin = 0 then p_RESULT := 'F';
      ELSE p_RESULT := 'T';
      END IF;
    END IF;
    if(p_condition = 'Graduate') then
      SELECT COUNT(*) INTO CheckLogin FROM Graduate 
      WHERE studentID = p_user_id and password = p_password;         
      IF CheckLogin = 0 then p_RESULT := 'F';
      ELSE p_RESULT := 'T';
      END IF;
    END IF;
    if(p_condition = 'Special') then
      SELECT COUNT(*) INTO CheckLogin FROM SpecialStudent 
      WHERE studentID = p_user_id and password = p_password;         
      IF CheckLogin = 0 then p_RESULT := 'F';
      ELSE p_RESULT := 'T';
      END IF;
    END IF;
    
  END login;  
  
  PROCEDURE enrollAddCourse
    (
      p_userID   IN INTEGER,
      p_condition IN VARCHAR2,
      p_courseID  IN VARCHAR2,
      p_program  IN VARCHAR2,
      p_sectionNo IN INTEGER,
      p_RESULT OUT CHAR
    )
  IS
    countFlag INTEGER;
    COUNTPROG INTEGER;
  BEGIN            
    if(p_condition = 'Graduate') then
      SELECT COUNT(*) INTO countFlag FROM ENROLLGRADUATE E WHERE p_courseID=E.COURSEID AND p_userID=E.STUDENTID;
      SELECT COUNT(*) INTO COUNTPROG FROM REGISTERGRAD R WHERE p_program=R.PROGID AND p_userID=R.STUDENTID;
      IF(countFlag = 0 AND COUNTPROG!= 0) THEN
        INSERT INTO ENROLLGRADUATE VALUES(p_userID,p_sectionNo,p_courseID,'20151',null,p_program);
        UPDATE Section SET capacityUsed=capacityUsed + 1 
        WHERE p_courseID=courseID and p_sectionNo=secNo;
      END IF;
    END IF;
    if(p_condition = 'Undergraduate') then
      SELECT COUNT(*) INTO countFlag FROM ENROLLUNDERGRADUATE E WHERE p_courseID=E.COURSEID AND p_userID=E.STUDENTID;
      SELECT COUNT(*) INTO COUNTPROG FROM REGISTERUNDER R WHERE p_program=R.PROGID AND p_userID=R.STUDENTID;
      IF(countFlag = 0 AND COUNTPROG!= 0) THEN
        INSERT INTO ENROLLUNDERGRADUATE VALUES(p_userID,p_sectionNo,p_courseID,'20151',null,p_program);
        UPDATE Section SET capacityUsed=capacityUsed + 1 
        WHERE p_courseID=courseID and p_sectionNo=secNo;
      END IF;
      
    END IF;
    if(p_condition = 'Special') then
      SELECT COUNT(*) INTO countFlag FROM ENROLLSPECIAL E WHERE p_courseID=E.COURSEID AND p_userID=E.STUDENTID;
      SELECT COUNT(*) INTO COUNTPROG FROM REGISTERSPEC R WHERE p_program=R.PROGID AND p_userID=R.STUDENTID;
      IF(countFlag = 0 AND COUNTPROG!= 0) THEN
        INSERT INTO ENROLLSPECIAL VALUES(p_userID,p_sectionNo,p_courseID,'20151',null,p_program);
        UPDATE Section SET capacityUsed=capacityUsed + 1 
        WHERE p_courseID=courseID and p_sectionNo=secNo;
      END IF;
      
    END IF;
    
    IF(countFlag!=0)THEN
      p_RESULT := 'P';
    END IF;
    
   EXCEPTION
    WHEN others then
    p_RESULT := 'F';
      
  END enrollAddCourse;
  
  PROCEDURE enrollUpdateSection
    (
      p_userID   IN INTEGER,
      p_condition IN VARCHAR2,
      p_courseID  IN VARCHAR2,
      p_sectionNo IN INTEGER,
      p_RESULT OUT CHAR
    )
  IS
    section VARCHAR2(30);
  BEGIN            
    if(p_condition = 'Graduate') then
      SELECT secNo INTO section FROM ENROLLGRADUATE WHERE studentID=p_userId and courseID=p_courseID;
      UPDATE ENROLLGRADUATE SET secNo=p_sectionNo
      WHERE studentID=p_userId and courseID=p_courseID;
      UPDATE Section SET capacityUsed=capacityUsed - 1 
      where courseID=p_courseID AND secNo=section;
      UPDATE Section SET capacityUsed=capacityUsed + 1 
      WHERE p_courseID=courseID and secNo=p_sectionNo;     
    END IF;
    if(p_condition = 'Undergraduate') then
      SELECT secNo INTO section FROM ENROLLUNDERGRADUATE WHERE studentID=p_userId and courseID=p_courseID and year='20151';
      UPDATE EnrollUnderGraduate SET secNo=p_sectionNo
      WHERE studentID=p_userId and courseID=p_courseID and year='20151';
      UPDATE Section SET capacityUsed=capacityUsed - 1 
      where courseID=p_courseID AND secNo=section;
      UPDATE Section SET capacityUsed=capacityUsed + 1 
      WHERE p_courseID=courseID and secNo=p_sectionNo;     
    END IF;
    if(p_condition = 'Special') then
      SELECT secNo INTO section FROM ENROLLSPECIAL WHERE studentID=p_userId and courseID=p_courseID;
      UPDATE ENROLLSPECIAL SET secNo=p_sectionNo
      WHERE studentID=p_userId and courseID=p_courseID;
      UPDATE Section SET capacityUsed=capacityUsed - 1 
      where courseID=p_courseID AND secNo=section;
      UPDATE Section SET capacityUsed=capacityUsed + 1 
      WHERE p_courseID=courseID and secNo=p_sectionNo;     
    END IF;
    
  EXCEPTION
    WHEN others then
    p_RESULT := 'F';
    
  END enrollUpdateSection;
  
  PROCEDURE enrollDropCourse
    (
      p_userID   IN INTEGER,
      p_condition IN VARCHAR2,
      p_courseID  IN VARCHAR2,
      p_sectionNo IN INTEGER
    )
  IS
    --section VARCHAR2;
  BEGIN         
    if(p_condition = 'Graduate') then
      Delete FROM EnrollGraduate where courseID=p_courseID and secNo=p_sectionNo and studentID=p_userID;
      UPDATE Section SET capacityUsed=capacityUsed - 1 
      WHERE p_courseID=courseID and p_sectionNo=secNo;
    END IF;
    if(p_condition = 'Undergraduate') then
      Delete FROM EnrollUnderGraduate where courseID=p_courseID and secNo=p_sectionNo and studentID=p_userID;
      UPDATE Section SET capacityUsed=capacityUsed - 1 
      WHERE p_courseID=courseID and p_sectionNo=secNo;
      
    END IF;
    if(p_condition = 'Special') then
      Delete FROM EnrollSpecial where courseID=p_courseID and secNo=p_sectionNo and studentID=p_userID;
      UPDATE Section SET capacityUsed=capacityUsed - 1 
      WHERE p_courseID=courseID and p_sectionNo=secNo;
      
    END IF;
  END enrollDropCourse;
  
   PROCEDURE getProfile
  (
    p_userID IN INTEGER,
    p_condition IN VARCHAR2,
    p_profile OUT SYS_REFCURSOR
  )
  IS 
  BEGIN
    if(p_condition = 'Graduate') then
      OPEN p_profile FOR
          Select * FROM Graduate
          WHERE p_userID = studentID;
    END IF;
    if(p_condition = 'Undergraduate') then
      OPEN p_profile FOR
        Select * FROM UnderGraduate
        WHERE p_userID = studentID;
    END IF;
    if(p_condition = 'Special') then
      OPEN p_profile FOR
        Select * FROM SpecialStudent
        WHERE p_userID = studentID;
    END IF;
  END getProfile;
  
  PROCEDURE updateProfile
  (
    p_userID IN INTEGER,
    p_condition IN VARCHAR2,
    p_address IN VARCHAR2,
    p_phone IN VARCHAR2,
    p_email IN VARCHAR2,
    p_RESULT OUT CHAR
  )
  IS 
  BEGIN
    if(p_condition = 'Graduate') then
      UPDATE Graduate
      SET address=p_address,
          phone=p_phone,
          email=p_email
      WHERE p_userID = studentID;
      p_RESULT := 'T';
    END IF;
    
    if(p_condition = 'Undergraduate') then
      UPDATE UnderGraduate
      SET address=p_address,
          phone=p_phone,
          email=p_email
      WHERE p_userID = studentID;
      p_RESULT := 'T';
    END IF;
    
    if(p_condition = 'Special') then
      UPDATE SpecialStudent
      SET address=p_address,
          phone=p_phone,
          email=p_email
      WHERE p_userID = studentID;
      p_RESULT := 'T';
    END IF;
    
    EXCEPTION
      WHEN others then
      p_RESULT := 'F';
 
    
  END updateProfile;
  
  PROCEDURE getSemester
  (
    p_userID IN INTEGER,
    p_condition IN VARCHAR2,
    p_program IN VARCHAR2,
    p_profile OUT SYS_REFCURSOR
  )
  IS
  BEGIN
    if(p_condition = 'Graduate') then
     OPEN p_profile FOR
      Select E.courseID,C.courseName,C.credit,E.secNo 
      FROM EnrollGraduate E,Course C 
      where E.studentID=p_userID 
      and C.courseID=E.courseID and E.program=p_program 
      and E.year='20151'; 
    END IF;
    if(p_condition = 'Undergraduate') then
     OPEN p_profile FOR
      Select E.courseID,C.courseName,C.credit,E.secNo 
      FROM EnrollUnderGraduate E,Course C 
      where E.studentID=p_userID 
      and C.courseID=E.courseID and E.program=p_program and E.year='20151';
    END IF;
    if(p_condition = 'Special') then
     OPEN p_profile FOR
      Select E.courseID,C.courseName,C.credit,E.secNo 
      FROM EnrollSpecial E,Course C 
      where E.studentID=p_userID 
      and C.courseID=E.courseID and E.program=p_program and E.year='20151'; 
    END IF;
  END getSemester;
  
  PROCEDURE getProgram
  (
    p_userID IN INTEGER,
    p_condition IN VARCHAR2,
    p_program IN VARCHAR2,
    p_profile OUT SYS_REFCURSOR
  )
  IS
  BEGIN
    if(p_condition = 'Graduate') then
     OPEN p_profile FOR
      Select P.deptID,P.levelProg,D.deptName 
      FROM RegisterGrad R, Program P,Department D 
      where R.studentID=p_userID and R.progID=P.progID and P.progID=p_program and P.DEPTID=R.DEPTID AND D.DEPTID=P.DEPTID;
    END IF;
    if(p_condition = 'Undergraduate') then
     OPEN p_profile FOR
      Select P.deptID,P.levelProg,D.deptName
      FROM RegisterUnder R, Program P, Department D 
      where R.studentID=p_userID and R.progID=P.progID and P.progID=p_program and D.DEPTID=P.DEPTID AND P.DEPTID=R.DEPTID ;
    END IF;
    if(p_condition = 'Special') then
     OPEN p_profile FOR
      Select P.deptID,P.levelProg ,D.deptName
      FROM RegisterSpec R, Program P,Department D 
      where R.studentID=p_userID and R.progID=P.progID and P.progID=p_program AND D.DEPTID=P.DEPTID AND P.DEPTID=R.DEPTID;
    END IF;
  END getProgram;
  
   PROCEDURE gradeSummary
  (
    p_userID IN INTEGER,
    p_condition IN VARCHAR2,
    p_profile OUT SYS_REFCURSOR
  )
  IS
  BEGIN
    if(p_condition = 'Graduate') then
     OPEN p_profile FOR
      Select * FROM EnrollUnderGraduate E,Course C 
      where E.studentID=p_userID and C.courseID=E.courseID;
    END IF;
    if(p_condition = 'Undergraduate') then
     OPEN p_profile FOR
      Select * FROM EnrollUnderGraduate   E,Course C 
      where E.studentID=p_userID and C.courseID=E.courseID;
    END IF;
    if(p_condition = 'Special') then
     OPEN p_profile FOR
      Select * FROM EnrollUnderGraduate E,Course C 
      where E.studentID=p_userID and C.courseID=E.courseID;
    END IF;
  END gradeSummary;
  
  PROCEDURE getGradeUpInfo
  (
    p_userID IN INTEGER,
    p_condition IN VARCHAR2,
    p_profile OUT SYS_REFCURSOR
  )
  IS
  BEGIN
    if(p_condition = 'Graduate') then
     OPEN p_profile FOR
      Select * FROM Graduate U, DEPARTMENT D, FACULTY F  
      where U.studentID=p_userID and U.deptID=D.deptID and F.FACID=D.facID;
    END IF;
    if(p_condition = 'Undergraduate') then
     OPEN p_profile FOR
      Select * FROM UnderGraduate U, DEPARTMENT D, FACULTY F  
      where U.studentID=p_userID and U.deptID=D.deptID and F.FACID=D.facID;
    END IF;
    if(p_condition = 'Special') then
     OPEN p_profile FOR
      Select * FROM SPECIALSTUDENT U, DEPARTMENT D, FACULTY F  
      where U.studentID=p_userID and U.deptID=D.deptID and F.FACID=D.facID;
    END IF;
  END getGradeUpInfo;
  
  PROCEDURE registration
  (
    p_userID IN INTEGER,
    p_condition IN VARCHAR2,
    p_profile OUT SYS_REFCURSOR
  )
  IS
  BEGIN
     if(p_condition = 'Graduate') then
     OPEN p_profile FOR
      Select E.courseID,C.courseName,C.credit,E.secNo 
      FROM EnrollGraduate E,Course C 
      where E.studentID=p_userID 
      and C.courseID=E.courseID
      and E.year='20151'; 
    END IF;
    if(p_condition = 'Undergraduate') then
     OPEN p_profile FOR
      Select E.courseID,C.courseName,C.credit,E.secNo 
      FROM EnrollUnderGraduate E,Course C 
      where E.studentID=p_userID 
      and C.courseID=E.courseID and E.year='20151';
    END IF;
    if(p_condition = 'Special') then
     OPEN p_profile FOR
      Select E.courseID,C.courseName,C.credit,E.secNo 
      FROM EnrollSpecial E,Course C 
      where E.studentID=p_userID 
      and C.courseID=E.courseID and E.year='20151'; 
    END IF;
    
  END registration;
  
  PROCEDURE getDepartment
  (
    p_profile OUT SYS_REFCURSOR
  )
  IS
  BEGIN
    OPEN p_profile FOR
      SELECT * FROM department D;
  END getDepartment;
  
  PROCEDURE getCourse
  (
    p_deptID IN VARCHAR2,
    p_profile OUT SYS_REFCURSOR
  )
  IS
    dID INTEGER;
  BEGIN
    SELECT deptID INTO dID FROM Department where deptName=p_deptID; 
    OPEN p_profile FOR
      SELECT * FROM COURSE C
      WHERE C.DEPTID=dID;
  END getCourse;
  
  PROCEDURE getSection
  (
    p_courseName IN VARCHAR2,
    p_profile OUT SYS_REFCURSOR
  )
  IS
    cID VARCHAR2(30);
  BEGIN
    Select courseID INTO cID from Course where courseName=p_courseName;
    OPEN p_profile FOR
      Select * from Section S, Professor P, SECTIONPROFESSOR Sp 
      where S.courseID=cID and Sp.courseID=cID and Sp.empID=P.empID and S.secNo=Sp.secNo; 
  END getSection;
  
  PROCEDURE getSectionInfo
  (
    p_courseCode IN INTEGER,
    p_section IN INTEGER,
    p_profile OUT SYS_REFCURSOR
  )
  IS
  BEGIN
    OPEN p_profile FOR
      Select * from SectionInfo where courseID=p_courseCode and secNo=p_section;
  END getSectionInfo;
  
  PROCEDURE loginStaff(
      p_user_id IN INTEGER,
      p_password   IN VARCHAR2,
      p_RESULT OUT CHAR)
  IS
   CheckLogin INTEGER;
  BEGIN
    SELECT COUNT(*) INTO CheckLogin FROM Staff 
    WHERE empID = p_user_id and password = p_password;         
    IF CheckLogin = 0 then p_RESULT := 'F';
    ELSE p_RESULT := 'T';
    END IF;  
  END loginStaff;
  
  PROCEDURE getStaffProfile
  (
    p_userID IN INTEGER,
    p_profile OUT SYS_REFCURSOR
  )
  IS 
  BEGIN
      OPEN p_profile FOR
          Select * FROM Staff
          WHERE empID=p_userID;
  END getStaffProfile;
  
  PROCEDURE updateStaffProfile
  (
    p_userID IN INTEGER,
    p_address IN VARCHAR2,
    p_phone IN VARCHAR2,
    p_email IN VARCHAR2,
    p_RESULT OUT CHAR
  )
  IS 
  BEGIN
      UPDATE Staff
      SET address=p_address,
          phoneNo=p_phone,
          email=p_email
      WHERE empID = p_userID;
      p_RESULT := 'T';
      
      EXCEPTION
      WHEN others then
      p_RESULT := 'F';
    
  END updateStaffProfile;
  
  PROCEDURE loginProf(
      p_user_id IN INTEGER,
      p_password   IN VARCHAR2,
      p_RESULT OUT CHAR)
  IS
   CheckLogin INTEGER;
  BEGIN
    SELECT COUNT(*) INTO CheckLogin FROM PROFESSOR 
    WHERE empID = p_user_id and password = p_password;         
    IF CheckLogin = 0 then p_RESULT := 'F';
    ELSE p_RESULT := 'T';
    END IF;  
  END loginProf;
  
  PROCEDURE getProfProfile
  (
    p_userID IN INTEGER,
    p_profile OUT SYS_REFCURSOR
  )
  IS 
  BEGIN
      OPEN p_profile FOR
          Select * FROM PROFESSOR
          WHERE empID=p_userID;
  END getProfProfile;
  
  PROCEDURE updateProfProfile
  (
    p_userID IN INTEGER,
    p_address IN VARCHAR2,
    p_phone IN VARCHAR2,
    p_email IN VARCHAR2,
    p_RESULT OUT CHAR
  )
  IS 
  BEGIN
      UPDATE PROFESSOR
      SET address=p_address,
          phoneNo=p_phone,
          email=p_email
      WHERE empID = p_userID;
      p_RESULT := 'T';
      
      EXCEPTION
      WHEN others then
      p_RESULT := 'F';
    
  END updateProfProfile;
  
  PROCEDURE getMySections
  (
    p_empID IN INTEGER,
    p_profile OUT SYS_REFCURSOR
  )
  IS
  BEGIN
    OPEN p_profile FOR
      Select *
      FROM SectionProfessor S, COURSE C, SECTION SE
      WHERE S.EMPID=p_empID and C.COURSEID=S.COURSEID 
      AND SE.COURSEID=C.COURSEID AND SE.SECNO=S.SECNO; 
  END getMySections;

  PROCEDURE getMySectionsUp
  (
    p_empID IN INTEGER,
    p_profile OUT SYS_REFCURSOR
  )
  IS
  BEGIN
    OPEN p_profile FOR
      Select *
      FROM PROFESSOR P, DEPARTMENT D
      WHERE P.EMPID=p_empID AND D.DEPTID=P.DEPTID;
  END getMySectionsUp;
  
  PROCEDURE getMyStudentList
  (
    p_courseID IN INTEGER,
    p_secNo IN INTEGER,
    p_profile OUT SYS_REFCURSOR
  )
  IS
  BEGIN
     OPEN p_profile FOR
      SELECT S.STUDENTID,S.NAME,S.SURNAME,S.EMAIL,E.GRADE FROM ENROLLUNDERGRADUATE E, UNDERGRADUATE S
      WHERE year='20151' AND E.SECNO=p_secNo 
      AND E.COURSEID=p_courseID AND S.STUDENTID=E.STUDENTID
      UNION
      SELECT S.STUDENTID,S.NAME,S.SURNAME,S.EMAIL,E.GRADE FROM ENROLLGRADUATE E, GRADUATE S
      WHERE year='20151' AND E.SECNO=p_secNo 
      AND E.COURSEID=p_courseID AND S.STUDENTID=E.STUDENTID
      UNION
      SELECT S.STUDENTID,S.NAME,S.SURNAME,S.EMAIL,E.GRADE FROM ENROLLSPECIAL E, SPECIALSTUDENT S
      WHERE year='20151' AND E.SECNO=p_secNo 
      AND E.COURSEID=p_courseID AND S.STUDENTID=E.STUDENTID;
  END getMyStudentList;
  
  PROCEDURE getMyStudents
  (
    p_empID IN INTEGER,
    p_profile OUT SYS_REFCURSOR
  )
  IS
  BEGIN
    OPEN p_profile FOR
      SELECT S.STUDENTID,S.NAME,S.SURNAME,S.EMAIL,S.CGPA FROM PROFADVISEDUNDERGRAD P, UNDERGRADUATE S
      WHERE S.STUDENTID=P.STUDENTID AND P.EMPID=p_empID  
      UNION
      SELECT S.STUDENTID,S.NAME,S.SURNAME,S.EMAIL,S.CGPA FROM PROFADVISEDGRAD P, GRADUATE S
      WHERE P.EMPID=p_empID 
      AND S.STUDENTID=P.STUDENTID;
  END getMyStudents;
  
  PROCEDURE loginAdmin(
      p_user_id IN INTEGER,
      p_password   IN VARCHAR2,
      p_RESULT OUT CHAR)
  IS
  CheckLogin INTEGER;
  BEGIN
    SELECT COUNT(*) INTO CheckLogin FROM ADMIN 
    WHERE adminID = p_user_id and adminpassword = p_password;         
    IF CheckLogin = 0 then p_RESULT := 'F';
    ELSE p_RESULT := 'T';
    END IF;  
  END loginAdmin;
  
  PROCEDURE showUnder(
       p_profile OUT SYS_REFCURSOR)
  IS
  BEGIN
     OPEN p_profile FOR
      Select * FROM UNDERGRADUATE;
  END showUnder;

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
      p_RESULT OUT CHAR)
    IS
      FLAG INTEGER;
    BEGIN
      SELECT COUNT(*) INTO FLAG FROM DEPARTMENT WHERE p_deptId=DEPTID;
      IF(FLAG!=0) THEN
        INSERT INTO UNDERGRADUATE VALUES(
        p_stuId,
        p_deptId,
        p_name,
        p_surname,
        p_natId,
        p_email,
        p_phone,
        p_address,
        p_password,
        p_cgpa);
      END IF;
      
      IF(FLAG=0)THEN
        p_RESULT := 'F';
      END IF;
      EXCEPTION
        WHEN others then
        p_RESULT := 'F';
      END addUnder;
    
  PROCEDURE showGrad(
       p_profile OUT SYS_REFCURSOR)
  IS
  BEGIN
     OPEN p_profile FOR
      Select * FROM GRADUATE;
  END showGrad;
  
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
      p_RESULT OUT CHAR)
    IS
      FLAG INTEGER;
    BEGIN
      SELECT COUNT(*) INTO FLAG FROM DEPARTMENT WHERE p_deptId=DEPTID;
      IF(FLAG!=0) THEN
        INSERT INTO GRADUATE VALUES(
        p_stuId,
        p_deptId,
        p_name,
        p_surname,
        p_natId,
        p_email,
        p_phone,
        p_address,
        p_password,
        p_cgpa,
        p_pre);
      END IF;
    
     IF(FLAG=0)THEN
        p_RESULT := 'F';
      END IF;
      
    EXCEPTION
        WHEN others then
        p_RESULT := 'F';
        
    END addGrad;
    
   PROCEDURE showSpec(
       p_profile OUT SYS_REFCURSOR)
  IS
  BEGIN
     OPEN p_profile FOR
      Select * FROM SPECIALSTUDENT;
  END showSpec;
  
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
      p_RESULT OUT CHAR)
    IS
      FLAG INTEGER;
    BEGIN
      SELECT COUNT(*) INTO FLAG FROM DEPARTMENT WHERE p_deptId=DEPTID;
      IF(FLAG!=0) THEN
        INSERT INTO SPECIALSTUDENT VALUES(
        p_stuId,
        p_deptId,
        p_name,
        p_surname,
        p_natId,
        p_email,
        p_phone,
        p_address,
        p_password,
        p_pre);
      END IF;
     
     IF(FLAG=0)THEN
        p_RESULT := 'F';
      END IF;
      
    EXCEPTION
        WHEN others then
        p_RESULT := 'F';
        
    END addSpec;
    
  PROCEDURE showProf(
       p_profile OUT SYS_REFCURSOR)
  IS
  BEGIN
     OPEN p_profile FOR
      Select * FROM PROFESSOR;
  END showProf;
  
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
      p_title  IN VARCHAR2,
      p_office  IN VARCHAR2,
      p_pre IN VARCHAR2,
      p_RESULT OUT CHAR)
    IS
      FLAG INTEGER;
    BEGIN
      SELECT COUNT(*) INTO FLAG FROM DEPARTMENT WHERE p_deptId=DEPTID;
      IF(FLAG!=0) THEN    
        INSERT INTO PROFESSOR VALUES(
        p_stuId,
        p_deptId,
        p_name,
        p_surname,
        p_natId,
        p_salary,
        p_email,
        p_phone,
        p_address,
        p_password,
        NULL,
        p_title,
        p_office,
        p_pre);
      END IF;
      
    IF(FLAG=0)THEN
        p_RESULT := 'F';
      END IF;
      
    EXCEPTION
        WHEN others then
        p_RESULT := 'F';
        
    END addProf;
    
  PROCEDURE showStaff(
       p_profile OUT SYS_REFCURSOR)
  IS
  BEGIN
     OPEN p_profile FOR
      Select * FROM STAFF;
  END showStaff;
  
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
      p_RESULT OUT CHAR)
    IS
      FLAG INTEGER;
    BEGIN
      SELECT COUNT(*) INTO FLAG FROM DEPARTMENT WHERE p_deptId=DEPTID;
      IF(FLAG!=0) THEN
        INSERT INTO STAFF VALUES(
        p_stuId,
        p_deptId,
        p_name,
        p_surname,
        p_natId,
        p_salary,
        p_email,
        p_phone,
        p_address,
        p_password,
        p_res);
      END IF;
      
      IF(FLAG=0)THEN
        p_RESULT := 'F';
      END IF;
      
    EXCEPTION
        WHEN others then
        p_RESULT := 'F';
        
    END addStaff;
  
  PROCEDURE showCourse(
       p_profile OUT SYS_REFCURSOR)
    IS
    BEGIN
      OPEN p_profile FOR
        Select * FROM Course;
    END showCourse;
       
  PROCEDURE addCourse(
      p_cid   IN INTEGER,
      p_did   IN INTEGER,
      p_name     IN VARCHAR2,
      p_credit  IN VARCHAR2,
      p_RESULT OUT CHAR)
    IS
       FLAG INTEGER;
    BEGIN
      SELECT COUNT(*) INTO FLAG FROM DEPARTMENT WHERE p_did=DEPTID;
      IF(FLAG!=0) THEN
        INSERT INTO COURSE VALUES(
          p_cid,
          p_did,
          p_name,
          p_credit,
          1);
      END IF;
      
      IF (FLAG=0) THEN
        p_RESULT := 'F';
      END IF;
    END addCourse;
    
  PROCEDURE showSection(
       p_profile OUT SYS_REFCURSOR)
    IS
    BEGIN
       OPEN p_profile FOR
        Select * FROM SECTION;
    END showSection;
       
  PROCEDURE addSection(
      p_sec   IN INTEGER,
      p_cid   IN INTEGER,
      p_cap    IN INTEGER,
      p_RESULT OUT CHAR)
    IS
      FLAG INTEGER;
    BEGIN
      SELECT COUNT(*) INTO FLAG FROM COURSE WHERE COURSE.COURSEID=p_cid;
        IF(FLAG!=0) THEN
        INSERT INTO SECTION VALUES(
          p_sec,
          p_cid,
          p_cap,
          0);
      END IF;
      
      IF (FLAG=0) THEN
        p_RESULT := 'F';
      END IF;
      
    END addSection;
    
  PROCEDURE showSectionInfo(
       p_profile OUT SYS_REFCURSOR)
    IS
    BEGIN
      OPEN p_profile FOR
        Select * FROM SECTIONINFO;
    END showSectionInfo;
       
  PROCEDURE addSectionInfo(
      p_cid   IN INTEGER,
      p_sid   IN INTEGER,
      p_dateSec    IN VARCHAR2,
      p_loc    IN VARCHAR2,
      p_start    IN VARCHAR2,
      p_end    IN VARCHAR2,
      p_RESULT OUT CHAR)
    IS
    FLAG INTEGER;
    BEGIN
      SELECT COUNT(*) INTO FLAG FROM SECTION 
      WHERE SECNO=p_sid AND COURSEID=p_cid;
      IF(FLAG!=0) THEN
        INSERT INTO SECTIONINFO VALUES(
          p_cid,
          p_sid,
          p_dateSec,
          p_loc,
          p_start, 
          p_end);
      END IF;
      
      IF (FLAG=0) THEN
        p_RESULT := 'F';
      END IF;
      
    END  addSectionInfo; 
    
  PROCEDURE showStudent(
       p_profile OUT SYS_REFCURSOR)
  IS
    FLAG INTEGER;
  BEGIN
    OPEN p_profile FOR
          Select U.STUDENTID, U.DEPTID, U.NAME, U.SURNAME 
          FROM UNDERGRADUATE U
          WHERE U.STUDENTID 
          NOT IN( SELECT STUDENTID FROM PROFADVISEDUNDERGRAD)
          UNION
          Select U.STUDENTID, U.DEPTID, U.NAME, U.SURNAME 
          FROM GRADUATE U
          WHERE U.STUDENTID 
          NOT IN( SELECT STUDENTID FROM PROFADVISEDGRAD);
  END  showStudent;
  
  PROCEDURE addAdvise(
      p_pid   IN INTEGER,
      p_sid   IN INTEGER,
      p_con   IN INTEGER,
      p_RESULT OUT CHAR)
  IS
    FLAG INTEGER;
    PROF INTEGER;
    STU INTEGER;
    STU2 INTEGER;
  BEGIN
      
    IF(p_con = 0) then  
      INSERT INTO PROFADVISEDUNDERGRAD VALUES(
        p_pid,
        p_sid);
    END IF;
    IF(p_con = 1) then  
      INSERT INTO PROFADVISEDGRAD VALUES(
        p_pid,
        p_sid);
    END IF;
    
     EXCEPTION
        WHEN others then
        p_RESULT := 'F';
        
  END addAdvise;
  
  PROCEDURE showUnderReg(
       p_profile OUT SYS_REFCURSOR)
  IS
    FLAG INTEGER;
  BEGIN
    OPEN p_profile FOR
      Select U.STUDENTID, U.DEPTID, U.NAME, U.SURNAME 
      FROM UNDERGRADUATE U
      WHERE U.STUDENTID 
      NOT IN( SELECT STUDENTID FROM REGISTERUNDER);
  END showUnderReg;     
  
  PROCEDURE showProg(
       p_profile OUT SYS_REFCURSOR)
  IS
    FLAG INTEGER;
  BEGIN
    OPEN p_profile FOR
      Select * 
      FROM PROGRAM;
  END showProg;    
  
  PROCEDURE addReg(
      p_pid   IN INTEGER,
      p_sid   IN INTEGER,
      p_did    IN INTEGER,
      p_flag IN INTEGER,
      p_RESULT OUT CHAR)
    IS
      FLAG INTEGER;
    BEGIN
     IF(p_flag=0) then
        SELECT COUNT(*) INTO FLAG FROM REGISTERUNDER R 
        WHERE R.STUDENTID=p_sid;
        IF(FLAG=0) THEN
        INSERT INTO REGISTERUNDER VALUES(
          p_pid,
          p_sid,
          p_did);
      END IF;
    END IF;
    IF(p_flag=1) THEN
      SELECT COUNT(*) INTO FLAG FROM REGISTERGRAD R 
      WHERE R.STUDENTID=p_sid;
        IF(FLAG=0) THEN
        INSERT INTO REGISTERGRAD VALUES(
          p_pid,
          p_sid,
          p_did);
      END IF;
    END IF;
    IF(p_flag=2) THEN 
      SELECT COUNT(*) INTO FLAG FROM REGISTERSPEC R 
      WHERE R.STUDENTID=p_sid;
        IF(FLAG=0) THEN
        INSERT INTO REGISTERSPEC VALUES(
          p_pid,
          p_sid,
          p_did);
      END IF;
    END IF;
    
   EXCEPTION
      WHEN others then
      p_RESULT := 'F';
        
  END addReg;
  
  PROCEDURE showGradReg(
       p_profile OUT SYS_REFCURSOR)
  IS
    FLAG INTEGER;
  BEGIN
    OPEN p_profile FOR
      Select U.STUDENTID, U.DEPTID, U.NAME, U.SURNAME 
      FROM GRADUATE U
      WHERE U.STUDENTID 
      NOT IN( SELECT STUDENTID FROM REGISTERGRAD);
  END showGradReg;     
  
  PROCEDURE showSpecReg(
       p_profile OUT SYS_REFCURSOR)
  IS
    FLAG INTEGER;
  BEGIN
    OPEN p_profile FOR
      Select U.STUDENTID, U.DEPTID, U.NAME, U.SURNAME 
      FROM SPECIALSTUDENT U
      WHERE U.STUDENTID 
      NOT IN( SELECT STUDENTID FROM REGISTERSPEC);
  END showSpecReg;   
  
  PROCEDURE showNotSection(
       p_profile OUT SYS_REFCURSOR)
  IS
    FLAG INTEGER;
  BEGIN
    OPEN p_profile FOR
      Select *
      FROM SECTION S
      WHERE (S.SECNO,S.COURSEID) 
      NOT IN( SELECT SECNO,COURSEID FROM SECTIONPROFESSOR);
  END showNotSection;     
  
  PROCEDURE addCourseProf(
      p_cid   IN INTEGER,
      p_sid   IN INTEGER,
      p_pid   IN INTEGER,
      p_RESULT OUT CHAR)
  IS
  BEGIN
    INSERT INTO SECTIONPROFESSOR VALUES(
          p_cid,
          p_sid,
          p_pid); 
          
    EXCEPTION
      WHEN others then
      p_RESULT := 'F';
      
  END addCourseProf;
      
END Database;