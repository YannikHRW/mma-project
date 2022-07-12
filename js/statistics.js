/**
 * Requests the statistics-json and creates an array of the selected month and year and then draws a graph.
 */
let xhr = new XMLHttpRequest();
xhr.open("GET", "statistics/orderStatistics.json", true);
xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
        let orders = JSON.parse(xhr.responseText);

        const monthSelect = document.getElementById("month-select");

        monthSelect.onchange = () => {
            let allOrders = orders[0].annualOrders;
            let valueArray = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            for (const yearOrder of allOrders) {
                if (monthSelect.value.includes(yearOrder.year)) {
                    let monthOrders = yearOrder.monthlyOrders;
                    for (const monthOrder of monthOrders) {
                        if (monthSelect.value.includes(monthOrder.month)) {
                            let dayOrders = monthOrder.dailyOrders;
                            for (const dayOrder of dayOrders) {
                                valueArray[parseInt(dayOrder.day) - 1] += dayOrder.ordersTotal;
                            }
                        }
                    }
                }
            }
            drawGraph(valueArray)
        }
    }
}
xhr.send(null);

/**
 * Makes the canvas responsive.
 *
 * @type {HTMLElement}
 */
let canvas = document.getElementById('myCanvas');
let heightRatio = 1.5;
canvas.height = canvas.width * heightRatio;

/**
 * Draws a graph.
 *
 * @param dataArr
 */
function drawGraph(dataArr) {

    let myCanvas = document.getElementById("myCanvas");
    let context = myCanvas.getContext("2d");

    // declare graph start and end
    let GRAPH_TOP = 25;
    let GRAPH_BOTTOM = 375;
    let GRAPH_LEFT = 25;
    let GRAPH_RIGHT = 1125;

    let GRAPH_HEIGHT = 350;
    let GRAPH_WIDTH = 450;

    let arrayLen = dataArr.length;

    let largest = 0;
    for (let i = 0; i < arrayLen; i++) {
        if (dataArr[i] > largest) {
            largest = dataArr[i];
        }
    }

    // clear other canvas
    context.clearRect(0, 0, 1220, 450);

    // set font for fillText()
    context.font = "16px Arial";

    // draw X and Y axis
    context.beginPath();
    context.moveTo(GRAPH_LEFT, GRAPH_BOTTOM);
    context.lineTo(GRAPH_RIGHT, GRAPH_BOTTOM);
    context.lineTo(GRAPH_RIGHT, GRAPH_TOP);
    context.stroke();

    // draw reference line
    context.beginPath();
    context.strokeStyle = "#BBB";
    context.moveTo(GRAPH_LEFT, GRAPH_TOP);
    context.lineTo(GRAPH_RIGHT, GRAPH_TOP);

    // draw reference value for orders
    context.fillText(largest, GRAPH_RIGHT + 15, GRAPH_TOP);
    context.stroke();

    // draw reference line
    context.beginPath();
    context.moveTo(GRAPH_LEFT, (GRAPH_HEIGHT) / 4 * 3 + GRAPH_TOP);
    context.lineTo(GRAPH_RIGHT, (GRAPH_HEIGHT) / 4 * 3 + GRAPH_TOP);

    // draw reference value for orders
    context.fillText(largest / 4, GRAPH_RIGHT + 15, (GRAPH_HEIGHT) / 4 * 3 + GRAPH_TOP);
    context.stroke();

    // draw reference line
    context.beginPath();
    context.moveTo(GRAPH_LEFT, (GRAPH_HEIGHT) / 2 + GRAPH_TOP);
    context.lineTo(GRAPH_RIGHT, (GRAPH_HEIGHT) / 2 + GRAPH_TOP);

    // draw reference value for orders
    context.fillText(largest / 2, GRAPH_RIGHT + 15, (GRAPH_HEIGHT) / 2 + GRAPH_TOP);
    context.stroke();

    // draw reference line
    context.beginPath();
    context.moveTo(GRAPH_LEFT, (GRAPH_HEIGHT) / 4 + GRAPH_TOP);
    context.lineTo(GRAPH_RIGHT, (GRAPH_HEIGHT) / 4 + GRAPH_TOP);

    // draw reference value for orders
    context.fillText(largest / 4 * 3, GRAPH_RIGHT + 15, (GRAPH_HEIGHT) / 4 + GRAPH_TOP);
    context.stroke();

    // draw titles
    context.fillText("Day of the month", GRAPH_RIGHT / 3, GRAPH_BOTTOM + 50);
    context.fillText("Orders", GRAPH_RIGHT + 30, GRAPH_HEIGHT / 2);

    context.beginPath();
    context.lineJoin = "round";
    context.strokeStyle = "black";

    context.moveTo(GRAPH_LEFT, (GRAPH_HEIGHT - dataArr[0] / largest * GRAPH_HEIGHT) + GRAPH_TOP);

    // draw reference value for day of the month
    context.fillText("1", 15, GRAPH_BOTTOM + 25);
    for (let i = 1; i < arrayLen; i++) {
        context.lineTo(GRAPH_RIGHT / arrayLen * i + GRAPH_LEFT, (GRAPH_HEIGHT - dataArr[i] / largest * GRAPH_HEIGHT) + GRAPH_TOP);
        context.fillText(i + 1, GRAPH_RIGHT / arrayLen * i + 15, GRAPH_BOTTOM + 25);
    }
    context.stroke();
}