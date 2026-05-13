@extends('layouts.admin')
@section('page-title')Compose Email@stop
@section('styles')
    @parent
    <style>
        .recipient-editor {
            align-items: center;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 0.5rem;
            cursor: text;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            min-height: 46px;
            padding: 6px 10px;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .recipient-editor:focus-within {
            border-color: #14b8a6;
            box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.12);
        }
        .recipient-editor.is-invalid {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
        }
        .recipient-chip {
            align-items: center;
            background: #f0fdfa;
            border: 1px solid #99f6e4;
            border-radius: 999px;
            color: #0f766e;
            display: inline-flex;
            font-size: 13px;
            gap: 6px;
            line-height: 1;
            padding: 5px 10px;
        }
        #recipient-chip-list { display: flex; flex-wrap: wrap; gap: 8px; }
        .recipient-chip-remove {
            background: transparent;
            border: 0;
            color: inherit;
            cursor: pointer;
            font-size: 14px;
            line-height: 1;
            padding: 0;
        }
        .recipient-input {
            border: 0;
            box-shadow: none;
            flex: 1 1 220px;
            min-width: 220px;
            outline: 0;
            padding: 4px 0;
            font-size: 0.875rem;
        }
        .email-send-row { display: flex; justify-content: flex-end; }
        .email-send-btn { min-width: 110px; position: relative; }
        .email-send-btn.is-loading { pointer-events: none; }
        .email-send-btn-spinner {
            border: 2px solid rgba(255, 255, 255, 0.35);
            border-top-color: #fff;
            border-radius: 50%;
            display: none;
            height: 16px;
            margin-right: 8px;
            vertical-align: text-bottom;
            width: 16px;
            animation: email-spin 0.7s linear infinite;
        }
        .email-send-btn.is-loading .email-send-btn-spinner { display: inline-block; }
        #content { min-height: 220px; }
        .email-feedback { display: none; margin-bottom: 20px; }
        .email-help-text { display: block; margin-top: 6px; color: #64748b; font-size: 0.8rem; }
        @keyframes email-spin { to { transform: rotate(360deg); } }
    </style>
@stop
@section('content')
    <div id="content-container">
        <div class="admin-page-header">
            <h2 class="admin-page-title">{{ __('Compose Email') }}</h2>
        </div>
        <div id="email-feedback" class="alert email-feedback" role="alert"></div>
        <div id="form-part">
            <form name="emailForm">
                <div class="form-group">
                    <label>{{ __('Recipients') }}</label>
                    <div class="recipient-editor" id="recipient-editor">
                        <div id="recipient-chip-list"></div>
                        <input class="recipient-input" id="recipient-input" type="text" placeholder="Type an email and press ; or Enter">
                    </div>
                    <input id="recipients" name="recipients" type="hidden">
                    <span class="email-help-text">Separate emails using semicolons — e.g. person1@example.com; person2@example.com</span>
                </div>
                <div class="form-group">
                    <label>{{ __('Subject') }}</label>
                    <input class="form-control" id="subject" name="subject" type="text" required>
                </div>
                <div class="form-group">
                    <label>{{ __('Content') }}</label>
                    <textarea class="form-control" id="content" name="content" required></textarea>
                    <span class="email-help-text">Plain text is supported. Basic HTML also works if you type it directly.</span>
                </div>
                <div class="form-group email-send-row">
                    <button type="submit" id="send" class="admin-btn admin-btn--primary email-send-btn">
                        <span class="email-send-btn-spinner" aria-hidden="true"></span>
                        <span class="email-send-btn-label">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            {{ __('Send') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('javascripts')
    @parent
    <script>
        $(document).ready(function() {
            var recipients = [];
            var $feedback = $('#email-feedback');
            var $recipientEditor = $('#recipient-editor');
            var $recipientInput = $('#recipient-input');
            var $recipientList = $('#recipient-chip-list');
            var $recipientField = $('#recipients');
            var $sendButton = $('#send');
            var $sendButtonLabel = $('.email-send-btn-label');

            function resetFeedback() {
                $feedback.hide().removeClass('alert-success alert-danger alert-warning').empty();
                $recipientEditor.removeClass('is-invalid');
            }

            function showFeedback(type, title, items) {
                var classes = { success: 'alert-success', danger: 'alert-danger', warning: 'alert-warning' };
                var html = '<strong>' + $('<div>').text(title).html() + '</strong>';
                if ($.isArray(items) && items.length) {
                    html += '<ul style="margin: 10px 0 0 20px;">';
                    $.each(items, function(_, item) { html += '<li>' + $('<div>').text(item).html() + '</li>'; });
                    html += '</ul>';
                }
                $feedback.removeClass('alert-success alert-danger alert-warning').addClass(classes[type] || 'alert-danger').html(html).show();
            }

            function setSending(isSending) {
                $sendButton.toggleClass('is-loading', isSending).prop('disabled', isSending);
                $recipientInput.prop('disabled', isSending);
                $('#subject').prop('disabled', isSending);
            }

            function normalizeRecipient(value) { return $.trim((value || '').replace(/[;,]+$/g, '')); }
            function isValidEmail(email) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email); }
            function syncRecipients() { $recipientField.val(recipients.join('; ')); }

            function renderRecipients() {
                $recipientList.empty();
                $.each(recipients, function(_, email) {
                    var $chip = $('<span class="recipient-chip"></span>');
                    var $removeButton = $('<button type="button" class="recipient-chip-remove" aria-label="Remove recipient">&times;</button>');
                    $chip.append($('<span></span>').text(email));
                    $removeButton.attr('data-email', email);
                    $chip.append($removeButton);
                    $recipientList.append($chip);
                });
                syncRecipients();
            }

            function addRecipients(values) {
                var invalidEmails = [], addedAny = false;
                $.each(values, function(_, value) {
                    var email = normalizeRecipient(value);
                    if (!email) return;
                    if (!isValidEmail(email)) { invalidEmails.push(email); return; }
                    if (recipients.findIndex(function(c) { return c.toLowerCase() === email.toLowerCase(); }) === -1) {
                        recipients.push(email); addedAny = true;
                    }
                });
                if (addedAny) renderRecipients();
                if (invalidEmails.length) { $recipientEditor.addClass('is-invalid'); showFeedback('danger', 'Some recipient addresses are invalid.', invalidEmails); }
            }

            function commitRecipientInput(force) {
                var rawValue = $recipientInput.val();
                var hasDelimiter = /[;\n,]/.test(rawValue);
                if (!rawValue) return;
                if (!force && !hasDelimiter) return;
                var parts = rawValue.split(/[;\n,]+/);
                if (!force && !/[;\n,]\s*$/.test(rawValue)) { $recipientInput.val(parts.pop()); } else { $recipientInput.val(''); }
                addRecipients(parts);
            }

            function removeRecipient(email) { recipients = recipients.filter(function(c) { return c !== email; }); renderRecipients(); }
            function getEditorContent() { return $('#content').val(); }

            function collectErrorMessages(payload) {
                var messages = [];
                if (payload && payload.responseJSON) {
                    if (payload.responseJSON.error && payload.responseJSON.error.message) messages.push(payload.responseJSON.error.message);
                    if (payload.responseJSON.error && $.isArray(payload.responseJSON.error.errors)) {
                        $.each(payload.responseJSON.error.errors, function(_, e) { if (e.email && e.message) messages.push(e.email + ': ' + e.message); });
                    }
                }
                if (!messages.length && payload && payload.statusText) messages.push(payload.statusText);
                if (!messages.length) messages.push('An unexpected error occurred while sending the email.');
                return messages;
            }

            $recipientEditor.on('click', function() { $recipientInput.trigger('focus'); });
            $recipientInput.on('input', function() { if (/[;\n,]/.test($(this).val())) { resetFeedback(); commitRecipientInput(false); } });
            $recipientInput.on('keydown', function(event) {
                if (event.key === 'Enter' || event.key === 'Tab') { event.preventDefault(); resetFeedback(); commitRecipientInput(true); }
                else if (event.key === 'Backspace' && !$recipientInput.val() && recipients.length) removeRecipient(recipients[recipients.length - 1]);
            });
            $recipientInput.on('blur', function() { resetFeedback(); commitRecipientInput(true); });
            $recipientList.on('click', '.recipient-chip-remove', function() { removeRecipient($(this).attr('data-email')); });
            $('form[name="emailForm"]').on('submit', function(event) { event.preventDefault(); $('#send').trigger('click'); });

            $('#send').click(function(ev) {
                ev.preventDefault();
                resetFeedback();
                commitRecipientInput(true);
                var content = getEditorContent();
                var data = { recipients: $recipientField.val(), subject: $('#subject').val(), content: content };
                if (!recipients.length) { $recipientEditor.addClass('is-invalid'); showFeedback('danger', 'Recipients must be filled out.'); $recipientInput.focus(); }
                else if (!data.subject) { showFeedback('danger', 'Subject must be filled out.'); $('#subject').focus(); }
                else if (!data.content) { showFeedback('danger', 'Content must be filled out.'); $('#content').focus(); }
                else {
                    setSending(true);
                    $.ajax({
                        url: "{{ url('/admin/email/send') }}",
                        type: 'POST', data: data, dataType: 'JSON',
                        success: function(response) {
                            var failedMessages = [];
                            if (response && response.data && $.isArray(response.data.failed)) {
                                $.each(response.data.failed, function(_, f) { if (f.email && f.message) failedMessages.push(f.email + ': ' + f.message); });
                            }
                            if (failedMessages.length) { showFeedback('warning', response.message || 'Email sent to some recipients.', failedMessages); }
                            else { showFeedback('success', response.message || 'Email sent successfully.'); }
                            recipients = []; renderRecipients(); $recipientInput.val(''); $('#subject').val(''); $('#content').val('');
                        },
                        error: function(xhr) { showFeedback('danger', 'Unable to send the email.', collectErrorMessages(xhr)); },
                        complete: function() { setSending(false); }
                    });
                }
            });
        });
    </script>
@stop
