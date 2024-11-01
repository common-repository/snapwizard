
function addEventListenerToDeleteExistingToken()
{
    let el = document.querySelector('.snapwizard_delete_existing_token');
    if (el) {
        el.addEventListener("click", (event) => {
            if (!confirm('Are you sure you want to delete the existing token?')){
                event.preventDefault()
            }
        });
    }
}

function addEventListenerToProcessFeedURL()
{
    let el = document.querySelector('.snapwizard_dashicons.process');
    if (el) {
        el.addEventListener("click", (event) => {
            if (!confirm('Are you sure you want to continue? \nIf you confirm you will run the engine processing the Instagram feed.')){
                event.preventDefault()
            } else {
                window.open(el.dataset.url, '_blank').focus();
            }
        });
    }
}

function addEventListenerToAjaxProcessFeedURL()
{
    let el = document.querySelector('.snapwizard_dashicons.process.ajax');
    if (el) {
        el.addEventListener("click", (event) => {
            if (!confirm('Are you sure you want to continue? \nIf you confirm you will run the engine processing the Instagram feed.')){
                event.preventDefault()
            } else {
                processFeedURL(el.dataset.url)
            }
        });
    }
}

function addEventListenerToCopyProcessFeedURLToClipboard()
{
    let els = document.querySelectorAll('.snapwizard_actions.copy-to-clipboard');
    if (els) {
        els.forEach((el) => {
            el.addEventListener("click", (event) => {

                event.preventDefault()

                let target = document.querySelector('#' + el.dataset.inputid);

                window.prompt("Copy to clipboard: Ctrl+C, Enter", target.value);
            });
        });
    }
}

function addEventListenerToShowAppSecret()
{
    let el = document.querySelector('.snapwizard_dashicons .dashicons-welcome-view-site');
    if (el) {
        el.addEventListener("click", (event) => {
            event.preventDefault()

            let target = document.querySelector('#' + el.dataset.inputid);

            target.type = (target.type === 'text') ? 'password' : 'text';
        });
    }
}

function processFeedURL(url)
{
    const newline = String.fromCharCode(13, 10);

    const logArea = document.getElementById('snapwizard_actions_process_ajax_log');
    logArea.style.display = 'block';
    logArea.value = '';

    const xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === XMLHttpRequest.OPENED) {
            logArea.value += "Opened connection...";
        } else if (xmlhttp.readyState === XMLHttpRequest.LOADING) {
            logArea.value += newline + "Loading...";
        } else if (xmlhttp.readyState === XMLHttpRequest.DONE) { // XMLHttpRequest.DONE == 4
            if (xmlhttp.status == 200) {
                clearInterval(refreshIntervalId);
                clearInterval(refreshIntervalWaitAMomentId);

                const responseJson = JSON.parse(xmlhttp.response);
                if(responseJson.response === 'success') {
                    logArea.value = responseJson.processed + " elements";
                    logArea.value += newline + "Done!";
                } else {
                    logArea.value = "An error occurred, check the log or try again.";
                }
            }
            else if (xmlhttp.status == 400) {
                logArea.value += newline + "There was an error 400";
            }
            else {
                logArea.value += newline + "Something else other than 200 was returned";
            }
        }
    };

    xmlhttp.responseType = 'json';
    xmlhttp.open("GET", url, true);
    xmlhttp.send();

    const refreshIntervalId = setInterval(function(){
        logArea.value += newline + "Still processing...";
    },7500);

    const refreshIntervalWaitAMomentId = setInterval(function(){
        logArea.value += newline + "Please wait a moment...";
    },20000);
}

window.addEventListener("DOMContentLoaded", (event) => {

    addEventListenerToDeleteExistingToken();
    addEventListenerToProcessFeedURL();
    addEventListenerToAjaxProcessFeedURL();
    addEventListenerToCopyProcessFeedURLToClipboard();
    addEventListenerToShowAppSecret();
});
