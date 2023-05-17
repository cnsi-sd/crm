<?php

namespace App\Enums;

abstract class PermissionEnum extends AbstractEnum
{
    /** Advanced admin */
    const USER_READ                     = 'user_read';
    const USER_EDIT                     = 'user_edit';
    const ROLE_READ                     = 'role_read';
    const ROLE_EDIT                     = 'role_edit';
    const DEFAULT_ANSWER_READ           = 'default_answer_read';
    const DEFAULT_ANSWER_EDIT           = 'default_answer_edit';
    const DEFAULT_ANSWER_LOCK           = 'default_answer_lock';
    const DEFAULT_ANSWER_EDIT_LOCKED    = 'default_answer_edit_locked';
    const REVIVAL_READ                  = 'revival_read';
    const REVIVAL_EDIT                  = 'revival_edit';
    const TICKET_READ                   = 'ticket_read';
    const TICKET_EDIT                   = 'ticket_edit';
    const CHANNEL_READ                  = 'channel_read';
    const CHANNEL_EDIT                  = 'channel_edit';
    const TAG_EDIT                      = 'tag_edit';
    const TAG_READ                      = 'tag_read';
    const TAG_LOCK                      = 'tag_lock';
    const TAG_EDIT_LOCKED               = 'tag_edit_locked';
    const BOT_CONFIG                    = 'bot_config';
    const MISC_CONFIG                   = 'misc_config';
    const SAV_NOTE_READ                 = 'sav_note_read';
    const SAV_NOTE_SEARCH               = 'sav_note_search';
    const SAV_NOTE_SHOW                 = 'sav_note_show';
    const SAV_NOTE_EDIT                 = 'sav_note_edit';
    const SAV_NOTE_DELETE               = 'sav_note_delete';

    const AGENT_DOC                     = 'agent_doc';
    const ADMIN_DOC                     = 'admin_doc';
    const JOB_READ                      = 'job_read';
}
