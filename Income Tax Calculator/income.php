<?php
    $salary =  $time = $taxFreeAllowance = "";
    $salaryError = $taxFreeAllowanceError = "";
    $salaryErrorClass = $taxFreeAllowanceErrorClass = "";

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        if(isset($_POST['calculate'])) {
            $validate = true;
            $salary = filterData($_POST['salary']);
            $time = $_POST['time'];
            $taxFreeAllowance = $_POST['taxFreeAllowance'];

            if (!preg_match('/^[0-9]+$/', $salary)) {
                $salaryError = "Only numbers are allowed";
                $validate = false;
            }

            if (!preg_match('/^[0-9]+$/', $taxFreeAllowance)) {
                $taxFreeAllowanceError = "Only numbers are allowed";
                $validate = false;
            }

        }
    }


function filterData($data) {
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="income.css">
    <title>Income Tax Calculator</title>
</head>
<body>
    <div class="wrapper">
        <form method="POST" class="form" action="<?php echo $_SERVER['PHP_SELF']; ?>"> 
            <div class="salary-container form-group">
                <p class="error"><?php echo $salaryError; ?></p>
                <span class="currency">$</span>
                <input type="text" name="salary"
                value="<?php if(isset($_POST['calculate'])) echo $salary; ?>"
                placeholder="Salary in USD" required>
            </div>
            
            <div class="allowance-container form-group">
            <p class="error"><?php echo $taxFreeAllowanceError; ?></p>
                <span class="currency">$</span>
                <input type="text"
                value="<?php if(isset($_POST['calculate'])) echo $taxFreeAllowance;?>"
                name="taxFreeAllowance" placeholder="Tax Free Allowance in USD" required> 
            </div>
            <div class="time-container form-group">
                <label class="monthly">
                    <input type="radio"
                    <?php if(isset($_POST['calculate'])) if($_POST['time'] == "monthly") echo "checked"; ?>
                    name="time" value="monthly" required> Monthly
                </label>
                <label class="yearly">
                    <input type="radio"
                    <?php if(isset($_POST['calculate'])) if($_POST['time'] == "yearly") echo "checked"; ?>
                    name="time" value="yearly"> Yearly
                </label>
            </div>
            <div class="btn-container form-group">
                <button type="submit" class="btn" name="calculate">Calculate</button>
            </div>
        </form>

        <?php 
            if(isset($_POST['calculate'])) {
                if($validate) {     
                    $salaryCalc = $salary;
                    $taxFreeAllowanceCalc = $taxFreeAllowance;
                    if($time == "monthly") {
                        $salaryCalc *= 12;
                        $taxFreeAllowance *= 12;
                    }

                    $totalSalaryMonthly = $salaryCalc / 12;
                    $totalSalaryYearly = $salaryCalc;

                    if($salaryCalc < 10000) {
                        $taxAmountMonthly = 0;
                        $taxAmountYearly = 0;
                    }
                    else if($salaryCalc < 25000){
                        $taxAmountMonthly = $totalSalaryMonthly * 0.11;
                        $taxAmountYearly = $totalSalaryYearly * 0.11;
                    }
                    else if($salaryCalc < 50000){
                        $taxAmountMonthly = $totalSalaryMonthly * 0.3;
                        $taxAmountYearly = $totalSalaryYearly * 0.3;
                    }
                    else {
                        $taxAmountMonthly = $totalSalaryMonthly * 0.45;
                        $taxAmountYearly = $totalSalaryYearly * 0.45;
                    }
                    if($salaryCalc > 10000) {
                        $socialSecurityMonthly = $totalSalaryMonthly * 0.04;
                        $socialSecurityYearly = $totalSalaryYearly * 0.04;
                    }
                    else {
                        $socialSecurityMonthly = 0;
                        $socialSecurityYearly = 0;
                    }

                    $salaryAfterTaxMonthly = $totalSalaryMonthly - $taxAmountMonthly - $socialSecurityMonthly + ($taxFreeAllowanceCalc / 12);
                    $salaryAfterTaxYearly = $totalSalaryYearly - $taxAmountYearly - $socialSecurityYearly + ($taxFreeAllowanceCalc);
                    

                    $totalSalaryMonthly = number_format((float)$totalSalaryMonthly, 1, '.', '');
                    $totalSalaryYearly = number_format((float)$totalSalaryYearly, 1, '.', '');
                    $taxAmountMonthly = number_format((float)$taxAmountMonthly, 1, '.', '');
                    $taxAmountYearly = number_format((float)$taxAmountYearly, 1, '.', '');
                    $socialSecurityMonthly = number_format((float)$socialSecurityMonthly, 1, '.', '');
                    $socialSecurityYearly = number_format((float)$socialSecurityYearly, 1, '.', '');
                    $salaryAfterTaxMonthly = number_format((float)$salaryAfterTaxMonthly, 1, '.', '');
                    $salaryAfterTaxYearly = number_format((float)$salaryAfterTaxYearly, 1, '.', '');
                    echo 
                    "
                    <table style='width: 100%; text-align: center;'  border = '1'>
                        <tr>
                            <th> </th>
                            <th>Monthly</th>
                            <th>Yearly</th>
                        </tr>
                        <tr>
                            <td>Total Salary</td>
                            <td>$totalSalaryMonthly$</td>
                            <td>$totalSalaryYearly$</td>
                        </tr>
                        <tr>
                            <td>Tax amount</td>
                            <td>$taxAmountMonthly$</td>
                            <td>$taxAmountYearly$</td>
                        </tr>
                        <tr>
                            <td>Social security fee</td>
                            <td>$socialSecurityMonthly$</td>
                            <td>$socialSecurityYearly$</td>
                        </tr>
                        
                        <tr>
                            <td>Salary after tax</td>
                            <td>$salaryAfterTaxMonthly$</td>
                            <td>$salaryAfterTaxYearly$</td>
                        </tr>
                    </table> 
                    ";
                }
            }
        ?>
    </div>
</body>
</html>
