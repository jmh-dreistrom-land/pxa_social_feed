<?php

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Pixelant\PxaSocialFeed\Domain\Model;

use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * This model represents a backend usergroup.
 */
class BackendUserGroup extends AbstractEntity
{
    public const FILE_OPPERATIONS             = 1;
    public const DIRECTORY_OPPERATIONS        = 4;
    public const DIRECTORY_COPY               = 8;
    public const DIRECTORY_REMOVE_RECURSIVELY = 16;

    /**
     * @var string
     */
    #[Extbase\Validate(['validator' => 'NotEmpty'])]
    protected $title = '';

    protected string $description = '';

    /**
     * @var ObjectStorage<BackendUserGroup>
     */
    protected ObjectStorage $subGroups;

    protected string $modules = '';

    protected string $tablesListening = '';

    protected string $tablesModify = '';

    protected string $pageTypes = '';

    protected string $allowedExcludeFields = '';

    protected string $explicitlyAllowAndDeny = '';

    protected string $allowedLanguages = '';

    protected bool $workspacePermission = false;

    protected string $databaseMounts = '';

    protected int $fileOperationPermissions = 0;

    protected string $tsConfig = '';


    public function __construct()
    {
        $this->initializeObject();
    }

    public function initializeObject(): void
    {
        $this->subGroups = new ObjectStorage();
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return ObjectStorage<BackendUserGroup> $subGroups
     */
    public function getSubGroups(): ObjectStorage
    {
        return $this->subGroups;
    }

    /**
     * @param ObjectStorage<BackendUserGroup> $subGroups
     */
    public function setSubGroups(ObjectStorage $subGroups): void
    {
        $this->subGroups = $subGroups;
    }

    public function addSubGroup(BackendUserGroup $beGroup): void
    {
        $this->subGroups->attach($beGroup);
    }

    public function removeSubGroup(BackendUserGroup $groupToDelete): void
    {
        $this->subGroups->detach($groupToDelete);
    }

    public function removeAllSubGroups(): void
    {
        $subGroups = clone $this->subGroups;
        $this->subGroups->removeAll($subGroups);
    }

    public function setModules(string $modules): void
    {
        $this->modules = $modules;
    }

    public function getModules(): string
    {
        return $this->modules;
    }

    public function setTablesListening(string $tablesListening): void
    {
        $this->tablesListening = $tablesListening;
    }

    public function getTablesListening(): string
    {
        return $this->tablesListening;
    }

    public function setTablesModify(string $tablesModify): void
    {
        $this->tablesModify = $tablesModify;
    }

    public function getTablesModify(): string
    {
        return $this->tablesModify;
    }

    public function setPageTypes(string $pageTypes): void
    {
        $this->pageTypes = $pageTypes;
    }

    public function getPageTypes(): string
    {
        return $this->pageTypes;
    }

    public function setAllowedExcludeFields(string $allowedExcludeFields): void
    {
        $this->allowedExcludeFields = $allowedExcludeFields;
    }

    public function getAllowedExcludeFields(): string
    {
        return $this->allowedExcludeFields;
    }

    public function setExplicitlyAllowAndDeny(string $explicitlyAllowAndDeny): void
    {
        $this->explicitlyAllowAndDeny = $explicitlyAllowAndDeny;
    }

    public function getExplicitlyAllowAndDeny(): string
    {
        return $this->explicitlyAllowAndDeny;
    }

    public function setAllowedLanguages(string $allowedLanguages): void
    {
        $this->allowedLanguages = $allowedLanguages;
    }

    public function getAllowedLanguages(): string
    {
        return $this->allowedLanguages;
    }

    public function setWorkspacePermissions(bool $workspacePermission): void
    {
        $this->workspacePermission = $workspacePermission;
    }

    public function getWorkspacePermission(): bool
    {
        return $this->workspacePermission;
    }

    public function setDatabaseMounts(string $databaseMounts): void
    {
        $this->databaseMounts = $databaseMounts;
    }

    public function getDatabaseMounts(): string
    {
        return $this->databaseMounts;
    }

    public function setFileOperationPermissions(int $fileOperationPermissions): void
    {
        $this->fileOperationPermissions = $fileOperationPermissions;
    }

    public function getFileOperationPermissions(): int
    {
        return $this->fileOperationPermissions;
    }

    /**
     * Check if file operations like upload, copy, move, delete, rename, new and
     * edit files is allowed.
     */
    public function isFileOperationAllowed(): bool
    {
        return $this->isPermissionSet(self::FILE_OPPERATIONS);
    }

    /**
     * Set the the bit for file operations are allowed.
     */
    public function setFileOperationAllowed(bool $value): void
    {
        $this->setPermission(self::FILE_OPPERATIONS, $value);
    }

    /**
     * Check if folder operations like move, delete, rename, and new are allowed.
     */
    public function isDirectoryOperationAllowed(): bool
    {
        return $this->isPermissionSet(self::DIRECTORY_OPPERATIONS);
    }

    /**
     * Set the the bit for directory operations are allowed.
     */
    public function setDirectoryOperationAllowed(bool $value): void
    {
        $this->setPermission(self::DIRECTORY_OPPERATIONS, $value);
    }

    /**
     * Check if it is allowed to copy folders.
     */
    public function isDirectoryCopyAllowed(): bool
    {
        return $this->isPermissionSet(self::DIRECTORY_COPY);
    }

    /**
     * Set the the bit for copy directories.
     */
    public function setDirectoryCopyAllowed(bool $value): void
    {
        $this->setPermission(self::DIRECTORY_COPY, $value);
    }

    /**
     * Check if it is allowed to remove folders recursively.
     */
    public function isDirectoryRemoveRecursivelyAllowed(): bool
    {
        return $this->isPermissionSet(self::DIRECTORY_REMOVE_RECURSIVELY);
    }

    /**
     * Set the the bit for remove directories recursively.
     */
    public function setDirectoryRemoveRecursivelyAllowed(bool $value): void
    {
        $this->setPermission(self::DIRECTORY_REMOVE_RECURSIVELY, $value);
    }

    public function setTsConfig(string $tsConfig): void
    {
        $this->tsConfig = $tsConfig;
    }

    public function getTsConfig(): string
    {
        return $this->tsConfig;
    }

    /**
     * Helper method for checking the permissions bitwise.
     */
    protected function isPermissionSet(int$permission): bool
    {
        return ($this->fileOperationPermissions & $permission) == $permission;
    }

    /**
     * Helper method for setting permissions bitwise.
     */
    protected function setPermission(int $permission, bool $value): void
    {
        if ($value) {
            $this->fileOperationPermissions |= $permission;
        } else {
            $this->fileOperationPermissions &= ~$permission;
        }
    }
}
