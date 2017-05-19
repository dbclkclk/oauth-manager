<!doctype html>
<html>
    <head>
        <title></title>
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
        <h1>Danforth Comparison Report</h1>

        <table cellpadding="1" cellspacing="1" style="width: 500px;">
            <tbody>
                <tr>
                    <td><strong>Student Name:</strong> <?php echo $displayData['student']->firstname . ' ' . $displayData['student']->lastname; ?></td>
                    <td>&nbsp;</td>
                    <td><strong>Age:</strong> 10</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Subject(s):</strong> <?php echo $displayData['subjectName']; ?></td>
                    <td><strong>Grade:</strong> <?php echo $displayData['student']->gradelevel; ?></td>
                </tr>
            </tbody>
        </table>

        <p>&nbsp;</p>

        <table border="1" cellpadding="1" cellspacing="1" style="width: 500px;">
            <tbody>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="2"><strong>Pre-Test</strong></td>
                    <td colspan="2"><strong>Post-Test</strong></td>
                </tr>
                <tr>
                    <td><strong>Benchmark Name</strong></td>
                    <td><strong>Date</strong></td>
                    <td><strong>Score</strong></td>
                    <td><strong>Date</strong></td>
                    <td><strong>Score</strong></td>
                </tr>

                <?php foreach ($displayData['goals'] as $goal) { ?>

                    <tr>
                        <td><?php echo $goal['goalName']; ?></td>
                        <td><?php echo $displayData['testDate']; ?></td>
                        <td><?php echo $goal['score']; ?>%</td>
                        <td></td>
                        <td></td>
                    </tr>

                <?php } ?>

                <tr>
                    <td><strong>Total Score(s):</strong></td>
                    <td>&nbsp;</td>
                    <td><strong><?php echo $displayData['averageScore']; ?>%</strong></td>
                    <td>&nbsp;</td>
                    <td><strong></strong></td>
                </tr>
                <tr>
                    <td><strong>Total Point Gain / Loss:</strong></td>
                    <td colspan="4" style="text-align: right;"><strong></strong></td>
                </tr>
                <tr>
                    <td><strong>Total % Increase / Decrease:</strong></td>
                    <td colspan="4" style="text-align: right;"><strong></strong></td>
                </tr>

            </tbody>

        </table>

    </body>

</html>
