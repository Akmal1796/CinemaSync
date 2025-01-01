'use strict';

const api_key = 'a4d0dea6657fe3e2d6bd991513a34da8';
const imageBaseURL = 'https://image.tmdb.org/t/p/';

/* Fetch Data from a server using the 'url' and passes the result in JSON to a 'callback' function, along with an optional parameter if has 'optionalParam'. */

const fetchDataFromServer = function (url, callback, optionalParam) {
    fetch(url)
        .then(response => response.json())
        .then(data => callback(data, optionalParam));
}

export { imageBaseURL, api_key, fetchDataFromServer };