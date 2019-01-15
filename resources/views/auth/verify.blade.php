@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('auth.verify_email_address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('auth.link_sent_to_email') }}
                        </div>
                    @endif

                    {{ __('auth.check_your_email_for_verification') }}
                    {{ __('auth.if_you_did_not_receive') }}, <a href="{{ route('verification.resend') }}">{{ __('auth.click_here') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
