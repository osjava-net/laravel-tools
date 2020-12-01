<?php
// API response is successful.
const SUCCESS_CODE = 0;

// Exception of secret
const ERR_SECRET_EXCEPTION = 1000;
/** Not found the sign parameter in the request or the sign value is wrong. */
const ERR_INVALID_SIGN = ERR_SECRET_EXCEPTION + 1;
/** The time of generation the sign value is expired. */
const ERR_EXPIRED_SIGN = ERR_SECRET_EXCEPTION + 2;
/** Not found the application key in the request. */
const ERR_INVALID_APPLICATION = ERR_SECRET_EXCEPTION + 3;
/** The application is disabled. */
const ERR_DISABLED_APPLICATION = ERR_SECRET_EXCEPTION + 4;

// Exception of clients that needs to be fixed by clients.
const ERR_REQUEST_EXCEPTION = 2000;
/** A parameters is not in the request or the parameter's value is wrong. */
const ERR_INVALID_PARAM = ERR_REQUEST_EXCEPTION + 1;