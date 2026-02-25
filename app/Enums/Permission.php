<?php

namespace App\Enums;

enum Permission: string
{
    case ProjectViewAny = 'projects.view_any';
    case ProjectView = 'projects.view';
    case ProjectCreate = 'projects.create';
    case ProjectUpdate = 'projects.update';
    case ProjectDelete = 'projects.delete';
    case ProjectAssignMembers = 'projects.assign_members';

    case TaskViewAny = 'tasks.view_any';
    case TaskView = 'tasks.view';
    case TaskCreate = 'tasks.create';
    case TaskUpdate = 'tasks.update';
    case TaskDelete = 'tasks.delete';

    case DocViewAny = 'docs.view_any';
    case DocView = 'docs.view';
    case DocCreate = 'docs.create';
    case DocUpdate = 'docs.update';
    case DocDelete = 'docs.delete';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
