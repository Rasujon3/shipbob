<div class="row">
    <div class="col-md-6 mb-3">
        <label><strong>UID:</strong></label>
        <p class="form-control-plaintext border p-2 bg-light">{{ $updateUser->uid ?? 'N/A' }}</p>
    </div>

    <div class="col-md-6 mb-3">
        <label><strong>Status:</strong></label>
        <p class="form-control-plaintext border p-2 bg-light">{{ $updateUser->status ?? 'N/A' }}</p>
    </div>

    <div class="col-md-6 mb-3">
        <label><strong>Username:</strong></label>
        <p class="form-control-plaintext border p-2 bg-light">{{ $updateUser->username ?? 'N/A' }}</p>
    </div>

    <div class="col-md-6 mb-3">
        <label><strong>Phone:</strong></label>
        <p class="form-control-plaintext border p-2 bg-light">{{ $updateUser->phone ?? 'N/A' }}</p>
    </div>

    <div class="col-md-6 mb-3">
        <label for="title"><strong>Withdraw Acc. No:</strong></label>
        <p class="form-control-plaintext border p-2 bg-light">{{ $updateUser?->paymentmethod?->account_number ?? 'N/A' }}</p>
    </div>

    <div class="col-md-6 mb-3">
        <label for="title"><strong>Withdraw Method:</strong></label>
        <p class="form-control-plaintext border p-2 bg-light">{{ $updateUser?->paymentmethod?->bank_name ?? 'N/A' }}</p>
    </div>

    <div class="col-md-6 mb-3">
        <label><strong>Balance:</strong></label>
        <p class="form-control-plaintext border p-2 bg-light">{{ $updateUser->main_balance ?? 'N/A' }}</p>
    </div>

    <div class="col-md-6 mb-3">
        <label><strong>Address:</strong></label>
        <p class="form-control-plaintext border p-2 bg-light">{{ $updateUser->address ?? 'N/A' }}</p>
    </div>

    <div class="col-md-6 mb-3">
        <label><strong>Last Login Time:</strong></label>
        <p class="form-control-plaintext border p-2 bg-light">
            {{ $updateUser->updated_at ? $updateUser->updated_at->format('d M Y, h:i A') : 'N/A' }}
        </p>
    </div>

    <div class="col-md-6 mb-3">
        <label><strong>Invitation Code:</strong></label>
        <p class="form-control-plaintext border p-2 bg-light">
            {{ $updateUser?->invitation_code ?? 'N/A' }}
        </p>
    </div>

    <div class="col-md-6 mb-3">
        <label><strong>Invited User:</strong></label>
        <p class="form-control-plaintext border p-2 bg-light">
            @if ($updateUser?->inviteUser?->user)
                {{ $updateUser->inviteUser->user->username }} ({{ $updateUser->inviteUser->user->uid }})
            @elseif ($updateUser?->invitedUserID && $updateUser->invitedUserID->username)
                {{ $updateUser->invitedUserID->username }} ({{ $updateUser->invitedUserID->uid }})
            @else
                N/A
            @endif
        </p>
    </div>

    <div class="col-md-6 mb-3">
        <label><strong>Reserved Amount:</strong></label>
        <p class="form-control-plaintext border p-2 bg-light">
            {{ ($updateUser->reserveAmount)
                ? $updateUser->reserveAmount->amount . ' (' . $updateUser->reserveAmount->value . $updateUser->reserveAmount->unit . ')'
                : 'N/A'
            }}
        </p>
    </div>

    <div class="col-md-6 mb-3">
        <label><strong>Number of Task will be block:</strong></label>
        <p class="form-control-plaintext border p-2 bg-light">
            {{ $updateUser->reserveAmount?->task_will_block ?? 'N/A' }}
        </p>
    </div>
</div>

<div class="modal-footer">
    <a href="{{ route('updateUser.edit', $updateUser->id) }}" class="btn btn-success">
        <i class="fas fa-edit"></i> Change Login Password
    </a>
    <a href="{{ route('updateUser.withdraw-password-edit', $updateUser->id) }}" class="btn btn-success">
        <i class="fas fa-edit"></i> Change Withdraw Password
    </a>
</div>
