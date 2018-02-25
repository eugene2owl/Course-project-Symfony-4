(function ($) {

    let defaults = {
        dataURL: 'http://symfony4.loc/admin/quiz',
        sortableColumns: ['id'],
        filterableColumns: ['id']
    };
    let options = {};

    $.fn.mySimplePlugin = function (params) {
        options = $.extend(defaults, params);

        drawSortOptions();

        $('.btn').click(function (e) {
            e.preventDefault();

            options.dataURL = this.href; // optional

            recognizeLastButton(this);
            let data = findOutData();
            data = {'data': data};
            $.ajax({
                url: options.dataURL,
                type: "POST",
                data: data,
                dataType: 'json',
                cache: false,
                success: success,
            });
        });

        $('.radio').click(function () {
            $('.last').click();
        });

        $('#filter').keyup(function () {
            $('.last').click();
        });

        function success(response) {
            updatePanel(response);
        }

        function findOutData() {
            let sortData = findOutSortedColomnAndOrder();
            let sortField = setSortField(sortData[0]);
            let sortOrder = setSortOrder(sortData[1]);
            let filter = setFilterData(findOutFilterData());
            let filterFields = setFilterFields();
            return [sortField, sortOrder, filter, filterFields];
        }

        function recognizeLastButton(checkedButton) {
            const defaultClass = 'btn btn-lg btn-default';
            let buttons = document.getElementsByClassName('btn');
            for (let buttonNumber = 0; buttonNumber < buttons.length; buttonNumber++) {
                buttons[buttonNumber].className = defaultClass;
            }
            checkedButton.className += " last";
        }

        function drawSortOptions() {
            let platform = $('#sortOptions');
            for (let number = 0; number < options.sortableColumns.length; number++) {
                $(platform).append($(
                    '        <label class="custom-control custom-radio" >\n' +
                    '            <input id="' + options.sortableColumns[number] + 'Sort" type="radio" name="radio" value="' + options.sortableColumns[number] + '" class="custom-control-input radio" checked>\n' +
                    '            <span class="custom-control-indicator"></span>\n' +
                    '            <span class="custom-control-description">' + options.sortableColumns[number] + '</span>\n' +
                    '        </label>\n'));
            }
        }

        function setSortField(field) {
            let data;
            switch (field) {
                case 'name':
                    data = {'sortbyfield': 'name'};
                    break;
                case 'id':
                    data = {'sortbyfield': 'id'};
                    break;
                case 'username':
                    data = {'sortbyfield': 'username'};
                    break;
                default:
                    data = "";
            }
            return data;
        }

        function setSortOrder(order) {
            return {'order': order};
        }

        function findOutSortedColomnAndOrder() {
            const cleanRadioClass = 'custom-control-input radio';
            const lastRadioClass = cleanRadioClass + ' lastRadio';
            let radios = document.getElementsByClassName('radio');
            let value = "";
            let order = 'ASC';
            let currentRadio;
            for (let currentRadioNumber = 0; currentRadioNumber < radios.length; currentRadioNumber++) {
                if (radios[currentRadioNumber].type === 'radio' && radios[currentRadioNumber].checked) {
                    if (radios[currentRadioNumber].className === (lastRadioClass)) {
                        order = 'DESC';
                    } else {
                        currentRadio = radios[currentRadioNumber];
                    }
                    value = radios[currentRadioNumber].value;
                }
                radios[currentRadioNumber].className = cleanRadioClass;
            }
            if (currentRadio !== undefined) {
                currentRadio.className = lastRadioClass;
            }
            return [value, order];
        }

        function setFilterData(text) {
            let data = {'pattern': ""};
            if (text !== "") {
                data = {'pattern': text};
            }
            return data;
        }

        function findOutFilterData() {
            return document.getElementById('filter').value;
        }

        function setFilterFields() {
            return {'filterableColumns': options.filterableColumns};
        }

        function updatePanel(response) {
            removeOldTable();
            let header = makeHeaderRow(response['entities']);
            let dataRows = makeDataCells(response['entities']);
            let table = makeTable(header, dataRows);
            putTable(table);
            disableImpossibleSorts();
        }

        function disableImpossibleSorts() {
            let currentButton = $('.last')[0];
            let radioButtons = $('.radio');
            let disabledRadioButtons = [];
            ableAll(radioButtons, false);
            switch (currentButton.id) {
                case 'quizButton':
                case 'userButton':
                    break;
                case 'questionButton':
                case 'answerButton':
                    disabledRadioButtons.push($('#usernameSort')[0]);
                    break;
                case 'resultButton':
                default:
                    disabledRadioButtons.push($('#usernameSort')[0]);
                    disabledRadioButtons.push($('#idSort')[0]);
                    disabledRadioButtons.push($('#nameSort')[0]);
                    break;
            }
            ableAll(disabledRadioButtons, true);
        }

        function ableAll(buttons, disabled) {
            for (let currentButtonNumber = 0; currentButtonNumber < buttons.length; currentButtonNumber++) {
                if (buttons[currentButtonNumber]) {
                    if (disabled === true) {
                        buttons[currentButtonNumber].disabled = true;
                    } else {
                        buttons[currentButtonNumber].disabled = false;
                    }
                }
            }
        }

        function removeOldTable() {
            let dataTable = document.getElementById('dataTable');
            if (dataTable !== null) {
                dataTable.parentNode.removeChild(dataTable);
            }
        }

        function makeTable(header, dataRows) {
            let myTable = document.createElement('table');
            myTable.setAttribute('id', 'dataTable');
            myTable.appendChild(header);
            dataRows.forEach(function (row) {
                myTable.appendChild(row);
            });
            myTable.setAttribute('class', 'table');
            return myTable;
        }

        function putTable(table) {
            let end = document.getElementById('#entities-grid');
            document.body.insertBefore(table, end);
        }

        function makeHeaderRow(assocArray) {
            let headerArray = [];
            for (let key in assocArray[0]) {
                headerArray.push(key);
            }
            let headerTagArray = [];
            headerArray.forEach(function (text) {
                headerTagArray.push(makeCell(text, true));
            });
            let currentHeader = 0;
            let headerRow = document.createElement('tr');
            headerTagArray.forEach(function (th) {
                if (currentHeader === 1) {
                    th.setAttribute('id', 'header2');
                }
                headerRow.appendChild(th);
                currentHeader++;
            });
            headerRow.setAttribute('class', 'thead-dark');
            return headerRow;
        }

        function makeOrdinarRow(assocArray, rowNumber) {
            let cellArray = [];
            for (let key in assocArray[rowNumber]) {
                cellArray = pushToCellArray(assocArray[rowNumber][key], cellArray);
            }
            let dataTagArray = [];
            cellArray.forEach(function (text) {
                dataTagArray.push(makeCell(text, false));
            });
            let ordinaryRow = document.createElement('tr');
            dataTagArray.forEach(function (th) {
                ordinaryRow.appendChild(th);
            });
            return ordinaryRow;
        }

        function pushToCellArray(data, cellArray) {
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

        function getNameOfArrayMember(member) {
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

        function makeList() {
            list = document.createElement('ol');
            return list;
        }

        function addToList(text, list) {
            let data = document.createTextNode(text);
            let li = document.createElement('li');
            li.appendChild(data);
            list.appendChild(li);
            return list;
        }

        function objectToString(object) {
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

        function makeDataCells(assocArray) {
            let rowArray = [];
            for (let rowNumber = 0; rowNumber < assocArray.length; rowNumber++) {
                rowArray.push(makeOrdinarRow(assocArray, rowNumber))
            }
            return rowArray;
        }

        function makeCell(text, isHeader) {
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
    };
})(jQuery);

$('#entities-grid').mySimplePlugin({
    dataURL: 'http://symfony4.loc/admin/user',
    sortableColumns: ['id', 'name', 'username'],
    filterableColumns: ['id', 'name', 'username'],
});