<!doctype html>
<html>
    <head>
        <title>HTML Editor - Full Version</title>
        <!-- Latest compiled and minified CSS -->
        <style>
            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            td, th {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
            }

            tr:nth-child(even) {
                background-color: #dddddd;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1 style="font-weight: normal; line-height: 1.2; color: rgb(51, 51, 51); ">Parent Proposal</h1>

            <p style="color: rgb(51, 51, 51);  font-size: 13px; line-height: 20.8px;"><strong>Student Name:</strong>&nbsp;<?php echo $displayData['student']->firstname . ' ' . $displayData['student']->lastname; ?>,&nbsp;<strong>Grade:</strong>&nbsp;<?php echo $displayData['student']->gradelevel; ?></p>

            <p style="color: rgb(51, 51, 51);  font-size: 13px; line-height: 20.8px;">Thank you for the opportunity to work with <?php echo $displayData['student']->firstname . ' ' . $displayData['student']->lastname; ?>. This Parent Proposal outlines the course work necessary to meet the objectives you asked us to manage.</p>

            <p style="color: rgb(51, 51, 51);  font-size: 13px; line-height: 20.8px;">The Danforth Assessment Test identifies student mastery as well as skill gaps. The test results are as follows:</p>

            <table class="table table-bordered" style="width: 100%">
                
                    <tr>
                        <th><strong>Test Type</strong></th>
                        <th><strong>Assessment Name</strong></th>
                        <th><strong>Test Score</strong></th>
                        <th><strong>Date</strong></th>
                    </tr>
                    <tr>
                        <td>Pre-Test <?php echo $displayData['subjectName']; ?></td>
                        <td><?php echo "Grade ".$displayData["grade"];  ?></td>
                        <td><?php echo $displayData['averageScore']; ?>%</td>
                        <td><?php echo $displayData['testDate']; ?></td>
                    </tr>
                
            </table>

            <p>The Achievement Goals are based on the lowest benchmark scores and are picked from your states requirements.&nbsp;The specific goals identified for <?php echo $displayData['student']->firstname . ' ' . $displayData['student']->lastname; ?> are:</p>

            <p><strong>Achievement Goals(s):</strong></p>

            <ol>

                <?php foreach ($displayData['goals'] as $goal) { ?>

                    <li><strong><?php echo $goal['goalName']; ?></strong> <?php echo $goal['goalDescription']; ?></li>

                <?php } ?>

            </ol>

            <p><strong>THE FOLLOWING RULES WILL GUIDE OUR MUTUAL RELATIONSHIP.&nbsp;TEACHPRO HAS MADE EVERY EFFORT TO PROVIDE THE BEST FORMATIVE&nbsp;TUTORING PROGRAM FOR THE LEAST EXPENSE TO YOU. THESE RULES ARE&nbsp;OUR MINIMUM REQUIREMENTS TO REMAIN SUCCESSFULLY IN BUSINESS.&nbsp;THERE CAN BE NO EXCEPTIONS. SORRY.</strong></p>

            <p><strong>CONTRACT:</strong> Our lowest per session fee is only available with a twenty session commitment, followed by optional increments of ten session commitments. We need the commitment to know our lower fee will continue long enough to cover our initial costs of curricula royalties, etc. Your understanding is appreciated.  TeachPro offers a slightly higher price per hour for customers not interested in making a 20 session commitment.  Customers that start with the non-commitment program can switch to the lower price program when they become comfortable with TeachPro and our tutoring programs.</p>

            <p><strong>CURRICULA:</strong> We do not use the student’s text books in the tutoring process. Skill gaps are usually a foundational issue. (Example: A student struggling with Algebra cannot learn the subject if they first did not learn how to add, subtract, multiply, divide, etc.) The assessment test will point us to the real problem(s), which usually precede the student’s current text books. The curricula we use will focus on the real problems.</p>

            <p><strong>ATTENDANCE/LATENESS:</strong> Consistency is very important in your child’s success. Please make every effort to have your child attend every scheduled session and be on time for each one. The teacher may have another student scheduled for tutoring following your child’s tutoring session. So he or she will need to leave at the scheduled completion time, regardless of the time they start. In fairness to TeachPro and the teacher, you will be billed for the full session time.</p>

            <p><strong>MISSED SESSIONS POLICY:</strong> You will be billed for missed sessions the same as if your student attended the session.   TeachPro is offering a very low tutoring price that depends on full attendance.  Vacation is the one exception.  Vacations must be scheduled a minimum of two-weeks in advance of the first missed session.</p>

            <p><strong>TUITION POLICY:</strong> Tuition is due in advance of instruction. Students will not be permitted to attend tutoring if payments are not current. TeachPro’s ‘Missed Sessions Policy’ will apply to the sessions missed due to no payment.  TeachPro accepts payments on whatever schedule works best for you the customer. You may pay weekly, bi-weekly or every four weeks. TeachPro accepts most credit and debit cards.</p>

            <p><strong>PROGRESS TESTING:</strong> After completing every 45 hours of instruction (45, 90, 135, etc.), your child will be given a progress test at no additional expense to you the customer.  A parent conference will be scheduled to review the results.</p>

            <p><strong>PROGRESS REPORTS:</strong> After completing every 45 hours of instruction (45, 90, 135, etc.), your child will be given a progress test.  You will not be charged for the Progress Report Tests.</p>

            <p><strong>NO HOMEWORK:</strong> Your child will complete all required work during the tutoring sessions. No additional work will be required between tutoring sessions. However, you as the parent can play a major part in your child’s success! Your enthusiasm, encouragement, positive attitude toward your child’s skills development, and consistent attendance are crucial to success. Constant praise of your child’s accomplishments and your continued interest in what they are learning (even if at times it may seem like small steps) will also enhance your child’s progress.</p>

            <p><strong>RECORDS:</strong> All testing materials and instructional records are confidential. All of TeachPro’s online work goes to a virtual server at a remote location. All of our computers could be stolen and your records are safe. None of that information is resident on our local computers.</p>

            <p><strong>INCENTIVE PROGRAM (OPTIONAL):</strong> It is our experience that some students feel their after school time is their time and tutoring is an intrusion. You and TeachPro understand the value of a good education. It is understandable when young students do not share that appreciation. But they do not have to be our age to understand rewards for accomplishments. So if you are hearing negative comments from your student regarding the tutoring sessions, we encourage you to set in-home goals with rewards. An example could be rewarding a 20% improvement on the next progress test. Only you know what kind of reward will incentivize your student.</p>

            <p><strong>TEACHER VACATIONS:</strong> Teacher’s take vacations too. Because we control the lesson plans from our Corporate office, we are very comfortable scheduling a replacement teacher when the regular teacher is on vacation. The TeachPro program is equal in strength to the best known tutoring programs. TeachPro is stronger than other programs that tutor in small groups. Our tutoring is a 2:1 student to teacher ratio and is less expensive because we do not pay rent that would be passed on to you. TeachPro provides the best tutoring experience at the most affordable price.  Each tutoring session will last 60 minutes.</p>
            <br />
            <p>Sincerely,</p>
            <p><strong>TeachPro Management</strong></p>
        </div>
    </body>
</html>
