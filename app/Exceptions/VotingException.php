<?php

namespace App\Exceptions;

use RuntimeException;

/** A vote that breaks a voting rule; the message is safe to show to the voter. */
class VotingException extends RuntimeException {}
