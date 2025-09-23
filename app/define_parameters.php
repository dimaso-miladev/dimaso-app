<?php

/**
 * --------------------------------------------------------------------------
 * Application-specific Constants
 * --------------------------------------------------------------------------
 *
 * This file contains application-specific constants that are used across
 * different parts of the codebase. Centralizing them here makes the code
 * more readable and easier to maintain.
 *
 */

// User Statuses
define('USER_STATUS_ACTIVE', 0);       // User is active and verified.
define('USER_STATUS_UNVERIFIED', 1);   // User has registered but not verified email.
define('USER_STATUS_BANNED', 2);       // User is banned.


// Email Verification
define('VERIFICATION_LINK_EXPIRATION_MINUTES', 60);
