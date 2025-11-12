<?php

namespace App\Policies;

use App\Models\User;

class UploadPolicy
{
  public function upload(User $user, string $importType): bool
    {

        $imports = config('import_data');
        if (!isset($imports[$importType])) {
            return false;
        }

        $requiredPermission = $imports[$importType]['permission_required'] ?? null;
        if (!$requiredPermission) {
            return false;
        }

        return $user->permissions()
            ->where('name', $requiredPermission)
            ->exists();
    }
}

