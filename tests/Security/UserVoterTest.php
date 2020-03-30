<?php
declare(strict_types=1);

namespace App\Tests\Security;

use App\Entity\User;
use App\Security\UserVoter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Security;

class UserVoterTest extends KernelTestCase
{
    // TODO rendre fonctionnel les tests qui ne le sont pas

    /**
     * @var UserVoter
     */
    private $voter;

    /**
     * @var Security
     */
    private $security;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$container;
        $this->security = new Security($container);
        $this->voter = new UserVoter($this->security);
    }

    public function testVoteOnSometingElse(): void
    {
        $token = $this->prophesize(TokenInterface::class);
        $this->assertSame(VoterInterface::ACCESS_ABSTAIN, $this->voter->vote($token->reveal(), null, ['FOOBAR']));
    }

    public function testVoteWhenNotConnected(): void
    {
        $user = new User();
        $token = $this->prophesize(TokenInterface::class);

        $this->assertSame(VoterInterface::ACCESS_DENIED, $this->voter->vote($token->reveal(), $user, [UserVoter::SHOW]));
        $this->assertSame(VoterInterface::ACCESS_DENIED, $this->voter->vote($token->reveal(), $user, [UserVoter::EDIT]));
    }

    public function provideVoteTests()
    {
        $userConnected = new User();
        $userConnected->setRoles(['ROLE_SUBSCRIBER']);
        yield 'ROLE_SUBSCRIBER can view and edit their profile' => [VoterInterface::ACCESS_GRANTED, $userConnected, $userConnected];

        /*
        $userConnected = new User();
        $userConnected->setRoles(['ROLE_SUBSCRIBER']);
        $user = new User();
        yield 'ROLE_SUBSCRIBER cannot view and edit other profile' => [VoterInterface::ACCESS_DENIED, $userConnected, $user];*/

        /*
        $user = new User();
        $admin = new User();
        $admin->setRoles(['ROLE_ADMIN']);
        yield 'ROLE_ADMIN can view and edit other profil' => [VoterInterface::ACCESS_GRANTED, $admin, $user];*/
    }

    /** @dataProvider provideVoteTests */
    public function testVote(int $expected, User $userConnected, User $user): void
    {
        $token = new UsernamePasswordToken($userConnected, 'password', 'main', $userConnected->getRoles());
        $this->assertSame($expected, $this->voter->vote($token, $user, [UserVoter::SHOW]));
        $this->assertSame($expected, $this->voter->vote($token, $user, [UserVoter::EDIT]));
    }
}
