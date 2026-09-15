<?php

namespace Tests\Feature;

use App\Models\User;
use App\Mail\ResetPasswordMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_page_renders_for_guests()
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
        $response->assertSee('Մոռացե՞լ եք Գաղտնաբառը');
    }

    public function test_send_reset_link_validation_fails_for_nonexistent_email()
    {
        $response = $this->post(route('password.email'), [
            'email' => 'nonexistent@elab.am',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_send_reset_link_sends_email_and_stores_token()
    {
        Mail::fake();

        $user = User::factory()->create(['email' => 'resettest@elab.am']);

        $response = $this->post(route('password.email'), [
            'email' => 'resettest@elab.am',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'resettest@elab.am',
        ]);

        Mail::assertSent(ResetPasswordMail::class, function ($mail) use ($user) {
            return $mail->hasTo('resettest@elab.am') && $mail->user->id === $user->id;
        });
    }

    public function test_reset_password_page_renders_with_token()
    {
        $response = $this->get(route('password.reset', ['token' => 'sample-token', 'email' => 'reset@elab.am']));

        $response->assertStatus(200);
        $response->assertSee('Նոր Գաղտնաբառի Սահմանում');
    }

    public function test_user_can_reset_password_with_valid_token()
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'resetvalid@elab.am',
            'password' => Hash::make('oldpassword123'),
        ]);

        $token = 'valid-reset-token-12345';
        DB::table('password_reset_tokens')->insert([
            'email' => 'resetvalid@elab.am',
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'resetvalid@elab.am',
            'password' => 'newbrandpassword123',
            'password_confirmation' => 'newbrandpassword123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        $this->assertTrue(Hash::check('newbrandpassword123', $user->fresh()->password));
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => 'resetvalid@elab.am']);
    }
}
