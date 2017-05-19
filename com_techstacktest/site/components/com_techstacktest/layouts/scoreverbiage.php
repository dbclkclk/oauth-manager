<html>

    <head>

    </head>

    <body>

        <p>Your student&rsquo;s test score is <?php echo $displayData; ?>%</p>

        <ul>
            <li>Most schools and school districts consider this grade to be the equivalent of an (

                <?php if ($displayData <= 69) { ?>

                F

            <?php } elseif ($displayData >= 70 && $displayData <= 77) { ?>

                D

            <?php } elseif ($displayData >= 78 && $displayData <= 85) { ?>

                C

            <?php } elseif ($displayData >= 86 && $displayData <= 92) { ?>

                B

            <?php } elseif ($displayData >= 93 && $displayData <= 100) { ?>

                A

            <?php } ?>
            )</li>
        <li>TeachPro considers test score letter grades to be the following:
            <ul>
                <?php if ($displayData <= 69) { ?>

                    <li>F Level score: Test scores 69% &amp; lower</li>

                <?php } elseif ($displayData >= 70 && $displayData <= 77) { ?>

                    <li>D Level score: Test scores 70% to 77%</li>

                <?php } elseif ($displayData >= 78 && $displayData <= 85) { ?>

                    <li>C Level score: Test scores 78% to 85%</li>

                <?php } elseif ($displayData >= 86 && $displayData <= 92) { ?>

                    <li>B Level score: Test scores 86% to 92%</li>

                <?php } elseif ($displayData >= 93 && $displayData <= 100) { ?>

                    <li>A Level score: Test scores 93% to 100%</li>

                <?php } ?>

            </ul>
        </li>
        <li>Your student may not be getting this test score in everything. But the Education Standards in your State are where the test questions come from. So technically this test score is representative of your student&rsquo;s current mastery of the tested subject. TeachPro uses this score to set the tutoring starting place for your student.</li>
    </ul>
        <p>TeachPro welcomes the opportunity to help. Your next step is to click on the &ldquo;<b>Enroll</b>&rdquo; button. It will take you to our registration page. There you will answer additional questions that will help us with the tutoring process. You will also select which program you want for your student, what method and timing of payments and what days and hours in those days do you want your student tutored each week. TeachPro then selects a teacher, from our faculty, that we know can meet your student&rsquo;s needs.</p>
    <p>Thank-you in advance for being a TeachPro valued customer.</p>
    <p>Make it a great day!</p>

</body>

</html>