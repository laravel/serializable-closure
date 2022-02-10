<?php

declare(strict_types=1);

namespace Tests\Stub\TypeHinted;

final class IssueFactory
{
    public function reproduceIssue(): Issue
    {
        return new Issue();
    }
}
