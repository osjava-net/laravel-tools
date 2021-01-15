<?php
/** API response is successful. */
const SUCCESS_CODE = 0;

/** General Secret Exception */
const ERR_SECRET_EXCEPTION = 1000;

/** Not found the sign parameter in the request or the sign value is wrong. */
const ERR_SIGN_INVALID = ERR_SECRET_EXCEPTION + 101;

/** The time of generation the sign value is expired. */
const ERR_SIGN_EXPIRED = ERR_SECRET_EXCEPTION + 102;

/** Not found the application key in the request. */
const ERR_APPLICATION_INVALID = ERR_SECRET_EXCEPTION + 103;

/** The application is disabled. */
const ERR_APPLICATION_DISABLED = ERR_SECRET_EXCEPTION + 104;

/** The request is unauthorized, there is no access token for example. */
const ERR_UNAUTHORIZED = ERR_SECRET_EXCEPTION + 105;

/**
 * The user made a valid request, but the server is refusing to serve the request,
 * due to a lake of permission to access the request resource.
 */
const ERR_FORBIDDEN = ERR_SECRET_EXCEPTION + 106;

/** API or method is defined but not be implemented */
const ERR_UNSUPPORTED_API = ERR_SECRET_EXCEPTION + 107;

/** Application customs its own error code about security from this value. */
const ERR_SECRET_EXTENDED = ERR_SECRET_EXCEPTION + 200;

/** General Application Exception */
const ERR_APP_EXCEPTION = 2000;

/** A parameter is missing in the request or its value is wrong. */
const ERR_INVALID_PARAM = ERR_APP_EXCEPTION + 101;

/** A configuration is missing or its value is empty. */
const ERR_MISSING_CONFIG = ERR_APP_EXCEPTION + 102;

/** An error code is returned when a command line was executed. */
const ERR_COMMAND_LINE = ERR_APP_EXCEPTION + 103;

const ERR_INVALID_HEADER = ERR_APP_EXCEPTION + 104;

const ERR_INVALID_STATUS = ERR_APP_EXCEPTION + 105;

/** Application extend error codes from the value */
const ERR_APP_EXTENDED = 3000;

/** Any errors are from the third party interfaces. */
const ERR_THIRD_EXCEPTION = 4000;