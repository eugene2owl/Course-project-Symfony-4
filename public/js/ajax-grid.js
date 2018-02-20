$(document).ready(function () {

    $('.btn').click(function (e) {
        e.preventDefault();

        var data = '';
        $.ajax({
            url: this.href,
            type: "POST",
            data: data,
            dataType: 'json',
            cache: false,
            success:function (response) {
                updatePanel(response);
            },
            error:function () {
                alert('err');
            }
        });
    })
});

function updatePanel(response)
{
    removeOldTable();
    let header = makeHeaderRow(response['entities']);
    let dataRows = makeDataCells(response['entities']);
    let table = makeTable(header, dataRows);
    putTable(table);
}

function removeOldTable()
{
    let dataTable = document.getElementById('dataTable');
    if (dataTable !== null) {
        dataTable.parentNode.removeChild(dataTable);
    }
}

function makeTable(header, dataRows)
{
    let myTable = document.createElement('table');
    myTable.setAttribute('id', 'dataTable');
    myTable.appendChild(header);
    dataRows.forEach(function (row) {
        myTable.appendChild(row);
    });
    myTable.setAttribute('border', '2');
    return myTable;
}

function putTable(table)
{
    let end = document.getElementById('#entities-grid');
    document.body.insertBefore(table, end);
}

function makeHeaderRow(assocArray)
{
    let headerArray = [];
    for (let key in assocArray[0]) {
        headerArray.push(key);
    }
    let headerTagArray = [];
    headerArray.forEach(function (text) {
        headerTagArray.push(makeCell(text, true));
    });
    let headerRow = document.createElement('tr');
    headerTagArray.forEach(function (th) {
       headerRow.appendChild(th);
    });
    return headerRow;
}

function makeOrdinarRow(assocArray, rowNumber)
{
    let cellArray = [];
    for (let key in assocArray[rowNumber]) {
        cellArray = pushToCellArray(assocArray[rowNumber][key], cellArray);
    }
    let dataTagArray = [];
    cellArray.forEach(function (text) {
        dataTagArray.push(makeCell(text, false));
    });
    let ordinarRow = document.createElement('tr');
    dataTagArray.forEach(function (th) {
        ordinarRow.appendChild(th);
    });
    return ordinarRow;
}

function pushToCellArray(data, cellArray)
{
    switch (data.constructor) {
        case Object:
            cellArray.push(objectToString(data));
            break;
        case Array:
            let list = makeList();
            data.forEach(function (member) {
                list = addToList(getNameOfArrayMember(member), list);
            });
            cellArray.push(list);
            break;
        default:
            cellArray.push(data);
    }
    return cellArray;
}

function getNameOfArrayMember(member)
{
    let name = "";
    switch (member.constructor) {
        case Object:
            name = objectToString(member);
            break;
        case Array:
            name = "(array)";
            break;
        default:
            name = member;
    }
    return name;
}

function makeList()
{
    list = document.createElement('ol');
    return list;
}

function addToList(text, list)
{
    let data = document.createTextNode(text);
    let li = document.createElement('li');
    li.appendChild(data);
    list.appendChild(li);
    return list;
}

function objectToString(object)
{
    let name = "";
    if (typeof object['username'] !== 'undefined') {
        name = object['username'];
    }
    if (typeof object['quizname'] !== 'undefined') {
        name = object['quizname'];
    }
    if (typeof object['text'] !== 'undefined') {
        name = object['text'];
    }
    return name;
}

function makeDataCells(assocArray)
{
    let rowArray = [];
    for (let rowNumber = 0; rowNumber < assocArray.length; rowNumber++) {
        rowArray.push(makeOrdinarRow(assocArray, rowNumber))
    }
    return rowArray;
}

function makeCell(text, isHeader)
{
    let myCell;
    isHeader ? myCell = document.createElement('th') : myCell = document.createElement('td');
    let content = "";
    if (typeof text !== 'object') {
        content = document.createTextNode(text);
    } else {
        content = text;
    }
    myCell.appendChild(content);
    return myCell;
}