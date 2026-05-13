<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use Symfony\Component\HttpFoundation\Response;

use Mail;
use Throwable;

use App\Mail\MassEmail;

class AdminController extends BaseController
{

   /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mail(Request $request)
    {
        $user = Auth::user();

        if (empty($user) || !method_exists($user, 'isAdmin') || !$user->isAdmin()) {
            return $this->sendUnauthorizedResponse();
        }

        $validator = Validator::make($request->all(), [
            'recipients' => 'required|string',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendInvalidResponse($validator->errors()->toArray());
        }

        $recipients = $this->parseRecipients($request->input('recipients'));

        if (empty($recipients)) {
            return $this->sendInvalidResponse([
                'recipients' => ['Please add at least one recipient email address.'],
            ]);
        }

        $invalidRecipients = array_values(array_filter($recipients, function ($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL) === false;
        }));

        if (!empty($invalidRecipients)) {
            return $this->sendInvalidResponse([
                'recipients' => array_map(function ($email) {
                    return "Invalid email address: {$email}";
                }, $invalidRecipients),
            ]);
        }

        $data = [
            'subject' => $request->subject,
            'content' => $request->content,
        ];
        $sentRecipients = [];
        $failedRecipients = [];
        $bccAddress = env('MAIL_FROM_ADDRESS_BCC');

        foreach ($recipients as $email) {
            try {
                $message = Mail::to($email);

                if (!empty($bccAddress)) {
                    $message->bcc($bccAddress);
                }

                $message->send(new MassEmail($data));
                $sentRecipients[] = $email;
            } catch (Throwable $exception) {
                Log::error('Unable to send compose email.', [
                    'admin_user_id' => $user->id,
                    'recipient' => $email,
                    'subject' => $request->subject,
                    'exception' => $exception,
                ]);

                $failedRecipients[] = [
                    'email' => $email,
                    'message' => $this->normalizeMailErrorMessage($exception->getMessage()),
                ];
            }
        }

        if (empty($sentRecipients)) {
            return $this->sendErrorResponse(
                'Unable to send the email.',
                Response::HTTP_BAD_GATEWAY,
                $failedRecipients
            );
        }

        return $this->sendValidResponse([
            'requested' => count($recipients),
            'sent' => $sentRecipients,
            'failed' => $failedRecipients,
        ], count($failedRecipients) > 0
            ? 'Email sent to some recipients, but some addresses failed.'
            : 'Email sent successfully.');
    }

    protected function parseRecipients($value)
    {
        $parts = preg_split('/[;,\r\n]+/', (string) $value);

        $recipients = array_map(function ($email) {
            return trim($email);
        }, $parts ?: []);

        $recipients = array_filter($recipients, function ($email) {
            return $email !== '';
        });

        return array_values(array_unique($recipients));
    }

    protected function normalizeMailErrorMessage($message)
    {
        $message = preg_replace('/\s+/', ' ', trim((string) $message));

        if (stripos($message, 'Email address is not verified') !== false) {
            return 'AWS SES rejected this recipient address because it is not verified or the account is still in sandbox mode.';
        }

        if (stripos($message, 'Connection could not be established') !== false || stripos($message, 'stream_socket_client') !== false) {
            return 'Unable to connect to the configured SMTP server.';
        }

        if (stripos($message, 'Failed to authenticate') !== false || stripos($message, 'Authentication credentials') !== false) {
            return 'SMTP authentication failed. Please verify the configured mail credentials.';
        }

        return $message !== '' ? $message : 'An unexpected mail error occurred.';
    }
}
