<?php

namespace App\Enums;

enum LeadStatus: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case Proposal = 'proposal';
    case Won = 'won';
    case Lost = 'lost';
    case Read = 'read';
    case Replied = 'replied';
    case Archived = 'archived';
}
