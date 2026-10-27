<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'submitted_at' => $this->submitted_at?->toIso8601String(),
            'resolved_at' => $this->resolved_at?->toIso8601String(),
            'withdrawn_at' => $this->withdrawn_at?->toIso8601String(),
            'withdrawal_reason' => $this->withdrawal_reason,
            'can_edit' => $request->user() ? $this->canBeEditedBy($request->user()) : false,
            'can_withdraw' => $request->user() ? $this->canBeWithdrawnBy($request->user()) : false,
            'category' => new ComplaintCategoryResource($this->whenLoaded('category')),
            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
            ],
            'assignee' => $this->assignee ? [
                'id' => $this->assignee->id,
                'name' => $this->assignee->name,
                'email' => $this->assignee->email,
            ] : null,
<<<<<<< HEAD
            'attachments' => $this->attachments->map(fn($attachment) => [
=======
            'attachments' => $this->attachments->map(fn ($attachment) => [
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
                'id' => $attachment->id,
                'name' => $attachment->original_name,
                'mime_type' => $attachment->mime_type,
                'size' => $attachment->size,
                'download_url' => url("/api/complaints/{$this->id}/attachments/{$attachment->id}"),
            ])->values(),
            'messages' => ComplaintMessageResource::collection($this->whenLoaded('messages')),
<<<<<<< HEAD
            'audit_logs' => $this->when($request->user()?->isStaff(), fn() => $this->auditLogs->map(fn($log) => [
=======
            'audit_logs' => $this->when($request->user()?->isStaff(), fn () => $this->auditLogs->map(fn ($log) => [
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
                'id' => $log->id,
                'action' => $log->action,
                'description' => $log->description,
                'user' => $log->user?->name,
                'created_at' => $log->created_at?->toIso8601String(),
            ])->values()),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
