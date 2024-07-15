'use strict';

import { api_key } from "./api.js";
import { sidebar } from "./sidebar.js";
import { createMovieCard } from "./movie-card.js";

const pageContent = document.querySelector("[page-content]");

sidebar();