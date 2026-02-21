<?php
namespace App\Services;

use App\Repositories\MemberRepository;

class MemberService
{
    protected MemberRepository $repo;

    public function __construct(MemberRepository $repo)
    {
        $this->repo = $repo;
    }

    public function createMember(array $data)
    {
        return $this->repo->create($data);
    }

    public function getMembers()
    {
        return $this->repo->paginate();
    }
}