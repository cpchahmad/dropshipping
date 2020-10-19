var RadioGroup = function (domNode) {

    this.domNode   = domNode;

    this.radioButtons = [];

    this.firstRadioButton  = null;
    this.lastRadioButton   = null;

};

RadioGroup.prototype.init = function () {

    // initialize pop up menus
    if (!this.domNode.getAttribute('role')) {
        this.domNode.setAttribute('role', 'radiogroup');
    }

    var rbs = this.domNode.querySelectorAll('[role=radio]');

    for (var i = 0; i < rbs.length; i++) {
        var rb = new RadioButton(rbs[i], this);
        rb.init();
        this.radioButtons.push(rb);

        console.log(rb);

        if (!this.firstRadioButton) {
            this.firstRadioButton = rb;
        }
        this.lastRadioButton = rb;
    }
    this.firstRadioButton.domNode.tabIndex = 0;
};

RadioGroup.prototype.setChecked  = function (currentItem) {
    for (var i = 0; i < this.radioButtons.length; i++) {
        var rb = this.radioButtons[i];
        rb.domNode.setAttribute('aria-checked', 'false');
        rb.domNode.tabIndex = -1;
    }
    currentItem.domNode.setAttribute('aria-checked', 'true');
    currentItem.domNode.tabIndex = 0;
    currentItem.domNode.focus();
};

RadioGroup.prototype.setCheckedToPreviousItem = function (currentItem) {
    var index;

    if (currentItem === this.firstRadioButton) {
        this.setChecked(this.lastRadioButton);
    }
    else {
        index = this.radioButtons.indexOf(currentItem);
        this.setChecked(this.radioButtons[index - 1]);
    }
};

RadioGroup.prototype.setCheckedToNextItem = function (currentItem) {
    var index;

    if (currentItem === this.lastRadioButton) {
        this.setChecked(this.firstRadioButton);
    }
    else {
        index = this.radioButtons.indexOf(currentItem);
        this.setChecked(this.radioButtons[index + 1]);
    }
};


/*
*   This content is licensed according to the W3C Software License at
*   https://www.w3.org/Consortium/Legal/2015/copyright-software-and-document
*
*   File:   RadioButton.js
*
*   Desc:   Radio button widget that implements ARIA Authoring Practices
*/

/*
*   @constructor RadioButton
*
*
*/
var RadioButton = function (domNode, groupObj) {

    this.domNode = domNode;
    this.radioGroup = groupObj;

    this.keyCode = Object.freeze({
        'RETURN': 13,
        'SPACE': 32,
        'END': 35,
        'HOME': 36,
        'LEFT': 37,
        'UP': 38,
        'RIGHT': 39,
        'DOWN': 40
    });
};

RadioButton.prototype.init = function () {
    this.domNode.tabIndex = -1;
    this.domNode.setAttribute('aria-checked', 'false');

    this.domNode.addEventListener('keydown',    this.handleKeydown.bind(this));
    this.domNode.addEventListener('click',      this.handleClick.bind(this));
    this.domNode.addEventListener('focus',      this.handleFocus.bind(this));
    this.domNode.addEventListener('blur',       this.handleBlur.bind(this));

};

/* EVENT HANDLERS */

RadioButton.prototype.handleKeydown = function (event) {
    var tgt = event.currentTarget,
        flag = false,
        clickEvent;

    //  console.log("[RadioButton][handleKeydown]: " + event.keyCode + " " + this.radioGroup)

    switch (event.keyCode) {
        case this.keyCode.SPACE:
        case this.keyCode.RETURN:
            this.radioGroup.setChecked(this);
            flag = true;
            break;

        case this.keyCode.UP:
            this.radioGroup.setCheckedToPreviousItem(this);
            flag = true;
            break;

        case this.keyCode.DOWN:
            this.radioGroup.setCheckedToNextItem(this);
            flag = true;
            break;

        case this.keyCode.LEFT:
            this.radioGroup.setCheckedToPreviousItem(this);
            flag = true;
            break;

        case this.keyCode.RIGHT:
            this.radioGroup.setCheckedToNextItem(this);
            flag = true;
            break;

        default:
            break;
    }

    if (flag) {
        event.stopPropagation();
        event.preventDefault();
    }
};

RadioButton.prototype.handleClick = function (event) {
    this.radioGroup.setChecked(this);
};

RadioButton.prototype.handleFocus = function (event) {
    this.domNode.classList.add('focus');
};

RadioButton.prototype.handleBlur = function (event) {
    this.domNode.classList.remove('focus');
};
