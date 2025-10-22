<?php

namespace App\Enums;

enum ActivityAction: string {
    // Authentication
    case LOGIN                     = 'login';
    case LOGOUT                    = 'logout';
    case OTP_SENT                   = 'otp_sent';
    case OTP_VERIFICATION_FAILED    = 'otp_verification_failed';
    case LOGIN_SUCCESS_EMAIL_OTP    = 'login_success_email_otp';
    case UNAUTHORIZED_ACCESS        = 'unauthorized_access';
    case OTP_REQUEST_INVALID        = 'otp_request_invalid_session';
    case OTP_RATE_LIMITED           = 'otp_request_rate_limited';
    case OTP_EMAIL_FAILED           = 'otp_email_failed';
    case OTP_VERIFY_INVALID         = 'otp_verify_invalid_session';

        // User Management
    case USER_CREATED               = 'user_created';
    case USER_UPDATED               = 'user_updated';
    case USER_DELETED               = 'user_deleted';
    case PASSWORD_CHANGED           = 'password_changed';
    case PASSWORD_RESET             = 'password_reset';
    case USER_VIEWED              = 'user_viewed';

        // Session / Account Status
        //case SESSION_TERMINATED         = 'session_terminated';
    case SESSIONS_REVOKED_ALL = 'session_terminated_all';
    case SESSION_REVOKED_SINGLE = 'session_terminated';

        // Access Control
    case ACCESS_GRANTED             = 'access_granted';
    case ACCESS_GRANTED_INHERITED   = 'access_granted_inherited';
    case ACCESS_DENIED              = 'access_denied';
    case UNAUTHORIZED_ATTEMPT       = 'unauthorized_attempt';
}
