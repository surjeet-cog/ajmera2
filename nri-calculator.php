<div class="loan-calculator-section mt-5">

    <div class="loan-calculator">

        <!-- =========================
             LEFT SIDE
        ========================= -->

        <div class="loan-controls pe-5 w-100">

            <!-- LOAN AMOUNT -->

            <div class="loan-control">

                <div class="loan-control-head">

                    <span>Loan amount</span>

                    <strong id="loanAmountDisplay">
                        ₹1,000
                    </strong>

                </div>

                <input type="range"
                       id="loanAmountRange"
                       min="1000"
                       max="10000000"
                       step="1000"
                       value="1000"
                       class="loan-slider">

                <div class="range-labels">

                    <span>₹ 1 Thousand</span>

                    <span>₹ 1 Cr</span>

                </div>

            </div>


            <!-- DURATION -->

            <div class="loan-control">

                <div class="loan-control-head">

                    <span>Duration Of Loan</span>

                    <strong id="loanDurationDisplay">
                        1 year
                    </strong>

                </div>

                <input type="range"
                       id="loanDurationRange"
                       min="12"
                       max="216"
                       step="12"
                       value="12"
                       class="loan-slider">
                
                <div class="range-labels">
                    <span>12 months</span>
                    <span>18 years</span>
                </div>

            </div>


            <!-- INTEREST RATE -->

            <div class="loan-control">

    <div class="loan-control-head">

        <span>Interest rate (% P.A)</span>

        <strong id="interestRateDisplay">
            1.25%
        </strong>

    </div>

    <input type="range"
           id="interestRateRange"
           min="1.25"
           max="15"
           step="0.05"
           value="1.25"
           class="loan-slider">

    <div class="range-labels">

        <span>1.25%</span>

        <span>15%</span>

    </div>

</div>


            <!-- BUTTON -->

           <!-- <button type="button"
                    class="check-eligibility-btn"
                    id="checkEligibility">

                CHECK ELIGIBILITY

            </button>-->

        </div>


        <!-- =========================
             RIGHT SIDE
        ========================= -->
    <div class="w-100 ps-5">
        <div class="loan-result w-100">


            <!-- EMI -->

            <div class="emi-heading">

                <div>
                    EMI starting from
                    <span>per month</span>
                </div>

                <strong id="emiAmount">
                    ₹1,000
                </strong>

            </div>


            <!-- GAUGE -->

            <div class="loan-gauge">

                <svg viewBox="0 0 400 220"
                     class="gauge-svg">

                    <!-- Grey semicircle -->

                    <path
                        d="M 40 200
                           A 160 160 0 0 1 360 200"
                        fill="none"
                        stroke="#eeeeee"
                        stroke-width="22"
                        stroke-linecap="butt"/>


                    <!-- Red semicircle -->

                    <path
                        id="gaugeRed"
                        d="M 40 200
                           A 160 160 0 0 1 360 200"
                        fill="none"
                        stroke="#df1f26"
                        stroke-width="22"
                        stroke-linecap="butt"
                        stroke-dasharray="502"
                        stroke-dashoffset="502"/>

                </svg>

            </div>


            <!-- LEGEND -->

            <div class="loan-legend">

                <div class="legend-item">

                    <span class="legend-color loan-color"></span>

                    <span>
                        Payable loan amount
                    </span>

                    <strong id="principalAmount">
                        ₹1,00,000
                    </strong>

                </div>


                <div class="legend-item">

                    <span class="legend-color interest-color"></span>

                    <span>
                        Total interest payable
                    </span>

                    <strong id="interestAmount">
                        ₹0
                    </strong>

                </div>

            </div>

        </div>
        <div class="total-payable-row">

        <span class="m-0"> Total amount payable </span>

        <strong class="m-0" id="totalAmount"> ₹1,00,000 </strong>

    </div>
</div>
    </div>


    <!-- TOTAL -->

    

</div>


<style>

/* =====================================================
   MAIN
===================================================== */




/* =====================================================
   CALCULATOR
===================================================== */

.loan-calculator {
    display: grid;
    grid-template-columns: 48% 52%;
    background: #f7f7f7;
}


/* =====================================================
   LEFT
===================================================== */

.loan-controls {
    padding: 35px 0;
    background: #f7f7f7;
}


.loan-control {
    margin-bottom: 48px;
}


/* =====================================================
   HEAD
===================================================== */

.loan-control-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
    font-size: 14px;
    color: #777;
}

.loan-control-head span, .range-labels span{
    margin:0;
}
.loan-control-head strong {
    color: #d71920;
    font-size: 14px;
    font-weight: 600;
    margin:0;
}


/* =====================================================
   RANGE
===================================================== */

.loan-slider {
    width: 100%;
    height: 3px;
    appearance: none;
    -webkit-appearance: none;
    border: 0;
    outline: none;
    cursor: pointer;

    background:
        linear-gradient(
            to right,
            #d71920 0%,
            #d71920 0%,
            #d8d8d8 0%,
            #d8d8d8 100%
        );
}


/* Chrome */

.loan-slider::-webkit-slider-thumb {
    appearance: none;
    -webkit-appearance: none;

    width: 15px;
    height: 15px;

    border-radius: 50%;

    background: #d71920;

    cursor: pointer;

    border: 0;
}


/* Firefox */

.loan-slider::-moz-range-thumb {
    width: 15px;
    height: 15px;

    border-radius: 50%;

    background: #d71920;

    cursor: pointer;

    border: 0;
}


/* =====================================================
   RANGE LABELS
===================================================== */

.range-labels {
    display: flex;
    justify-content: space-between;

    margin-top: 18px;

    font-size: 13px;
    color: #888;
}


/* =====================================================
   BUTTON
===================================================== */

.check-eligibility-btn {
    display: inline-flex;

    min-width: 202px;
    height: 42px;

    align-items: center;
    justify-content: center;

    border: 0;

    background: #d71920;

    color: #fff;

    font-size: 13px;

    cursor: pointer;

    transition: all 0.3s ease;
}


.check-eligibility-btn:hover {
    background: #b9141a;
}


/* =====================================================
   RIGHT RESULT
===================================================== */

.loan-result {
    background: #fff;

    padding: 35px 45px 35px;

    min-height: 450px;
}


/* =====================================================
   EMI
===================================================== */

.emi-heading {
    display: flex;

    align-items: center;

    justify-content: center;

    gap: 12px;

    margin-top: 25px;

    color: #777;

    font-size: 14px;
}


.emi-heading div {
    line-height: 1.3;
}


.emi-heading span {
    display: block;

    color: #d71920;

    font-weight: 500;
}


.emi-heading strong {
    color: #d71920;

    font-size: 42px;

    font-weight: 600;

    line-height: 1;
}


/* =====================================================
   GAUGE
===================================================== */

.loan-gauge {
    width: 100%;

    max-width: 390px;

    margin: 22px auto 0;

    position: relative;
}


.gauge-svg {
    width: 100%;

    display: block;
}


/* =====================================================
   LEGEND
===================================================== */

.loan-legend {
    width: 85%;

    max-width: 390px;

    margin: -5px auto 0;
}


.legend-item {
    display: grid;

    grid-template-columns: 14px 1fr auto;

    align-items: center;

    gap: 10px;

    margin-bottom: 17px;

    font-size: 13px;

    color: #777;
}


.legend-item strong {
    font-weight: 400;

    color: #777;

    white-space: nowrap;
}


.legend-color {
    width: 14px;

    height: 14px;

    display: block;
}


.loan-color {
    background: #d3d3d3;
}


.interest-color {
    background: #d71920;
}


/* =====================================================
   TOTAL
===================================================== */

.total-payable-row {
    display: flex;

    justify-content: space-between;

    align-items: center;

    padding: 20px 0 0;

    font-size: 14px;

    color: #777;
}


.total-payable-row strong {
    color: #d71920;

    font-size: 16px;

    font-weight: 500;
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width: 991px) {

    .loan-calculator {
        grid-template-columns: 1fr;
    }

    .loan-result {
        min-height: auto;
    }

}


@media (max-width: 767px) {

    .loan-calculator-section {
        margin: 30px 15px;
    }

    .loan-controls {
        padding: 30px 20px;
    }

    .loan-result {
        padding: 30px 20px;
    }

    .emi-heading {
        flex-direction: column;

        text-align: center;

        gap: 10px;
    }

    .emi-heading strong {
        font-size: 34px;
    }

    .loan-legend {
        width: 100%;
    }

    .total-payable-row {
        padding: 20px;

        gap: 20px;
    }

}

</style>


<script>

document.addEventListener("DOMContentLoaded", function () {


    /* =====================================================
       ELEMENTS
    ===================================================== */

    const loanAmountRange =
        document.getElementById("loanAmountRange");

    const loanDurationRange =
        document.getElementById("loanDurationRange");

    const interestRateRange =
        document.getElementById("interestRateRange");


    const loanAmountDisplay =
        document.getElementById("loanAmountDisplay");

    const loanDurationDisplay =
        document.getElementById("loanDurationDisplay");

    const interestRateDisplay =
        document.getElementById("interestRateDisplay");


    const emiAmount =
        document.getElementById("emiAmount");

    const principalAmount =
        document.getElementById("principalAmount");

    const interestAmount =
        document.getElementById("interestAmount");

    const totalAmount =
        document.getElementById("totalAmount");


    const gaugeRed =
        document.getElementById("gaugeRed");


    /* =====================================================
       FORMAT INDIAN CURRENCY
    ===================================================== */

    function formatIndianNumber(number) {

        return Number(number).toLocaleString(
            "en-IN",
            {
                maximumFractionDigits: 0
            }
        );

    }


    function formatCurrency(number) {

        return "₹" + formatIndianNumber(number);

    }


    /* =====================================================
       RANGE BACKGROUND
    ===================================================== */

    function updateSlider(slider) {

        const min =
            Number(slider.min);

        const max =
            Number(slider.max);

        const value =
            Number(slider.value);


        const percentage =
            ((value - min) / (max - min)) * 100;


        slider.style.background =
            `linear-gradient(
                to right,
                #d71920 0%,
                #d71920 ${percentage}%,
                #d8d8d8 ${percentage}%,
                #d8d8d8 100%
            )`;

    }


    /* =====================================================
       CALCULATE EMI
    ===================================================== */

    function calculateLoan() {


        const principal =
            Number(loanAmountRange.value);


        const months =
            Number(loanDurationRange.value);


        const annualInterest =
            Number(interestRateRange.value);


        /*
         * Monthly interest rate
         */

        const monthlyRate =
            annualInterest / 12 / 100;


        let emi;


        /*
         * Zero interest
         */

        if (monthlyRate === 0) {

            emi =
                principal / months;

        }

        /*
         * Normal EMI formula
         */

        else {

            emi =
                principal *
                monthlyRate *
                Math.pow(
                    1 + monthlyRate,
                    months
                ) /
                (
                    Math.pow(
                        1 + monthlyRate,
                        months
                    ) - 1
                );

        }


        /* Total payable */

        const totalPayable =
            emi * months;


        /* Total interest */

        const totalInterest =
            totalPayable - principal;


        /* =================================================
           UPDATE VALUES
        ================================================= */


        loanAmountDisplay.textContent =
            formatCurrency(principal);


        loanDurationDisplay.textContent =
            months / 12 === 1
                ? "1 year"
                : (months / 12) + " years";


        interestRateDisplay.textContent =
            annualInterest.toFixed(1) + "%";


        emiAmount.textContent =
            formatCurrency(emi);


        principalAmount.textContent =
            formatCurrency(principal);


        interestAmount.textContent =
            formatCurrency(totalInterest);


        totalAmount.textContent =
            formatCurrency(totalPayable);


        /* =================================================
           GAUGE
        ================================================= */


        /*
         * Semicircle path length approx 502
         */

        const gaugeLength = 502;


        /*
         * Interest percentage of total payable
         */

        let interestPercentage =
            totalInterest / totalPayable;


        /*
         * Convert to semicircle
         */

        const dashOffset =
            gaugeLength -
            (
                gaugeLength *
                interestPercentage
            );


        gaugeRed.style.strokeDashoffset =
            dashOffset;


        /* =================================================
           UPDATE SLIDERS
        ================================================= */

        updateSlider(
            loanAmountRange
        );

        updateSlider(
            loanDurationRange
        );

        updateSlider(
            interestRateRange
        );

    }


    /* =====================================================
       EVENTS
    ===================================================== */

    loanAmountRange.addEventListener(
        "input",
        calculateLoan
    );


    loanDurationRange.addEventListener(
        "input",
        calculateLoan
    );


    interestRateRange.addEventListener(
        "input",
        calculateLoan
    );


    /* =====================================================
       CHECK ELIGIBILITY
    ================================================= */

    document
        .getElementById("checkEligibility")
        .addEventListener(
            "click",
            function () {

                /*
                 * Yahan tum apna form/modal open
                 * karwa sakte ho.
                 */

                alert(
                    "Your eligibility request has been initiated."
                );

            }
        );


    /* =====================================================
       INITIAL CALCULATION
    ===================================================== */

    calculateLoan();

});

</script>