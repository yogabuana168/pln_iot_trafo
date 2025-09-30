@extends('partials.layouts.master')

@section('title', 'User Group')
@section('pagetitle', 'User Group')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css" rel="stylesheet">
    <style>
        .group-card {
            min-height: 400px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .group-card:hover {
            border-color: var(--bs-primary);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .user-item {
            cursor: move;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            position: relative;
        }
        
        .user-item:hover {
            background-color: #f8f9fa;
            border-left-color: var(--bs-primary);
        }
        
        .user-item.leader {
            background-color: #fff3e0;
            border-left: 3px solid #ff9800;
            font-weight: 600;
        }
        
        .user-item.leader:hover {
            background-color: #ffe0b2;
            border-left-color: #f57c00;
        }
        
        .user-item.leader .fw-semibold {
            color: #0d6efd !important;
        }
        
        .user-item.leader .text-muted {
            color: #dc3545 !important;
        }
        
        .user-item.member {
            margin-left: 30px;
            border-left: 3px solid transparent;
        }
        
        .user-item.member::before {
            content: '';
            position: absolute;
            left: -30px;
            top: 50%;
            width: 20px;
            height: 1px;
            background-color: #dee2e6;
        }
        
        .leader-badge {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: 600;
        }
        
        .set-leader-btn {
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        
        .user-item:hover .set-leader-btn {
            opacity: 1;
        }
        
        .user-item.sortable-drag {
            opacity: 0.5;
            transform: rotate(2deg);
        }
        
        .user-item.sortable-ghost {
            background-color: #e3f2fd;
            border-left-color: var(--bs-primary);
        }
        
        .user-list {
            min-height: 300px;
            max-height: 500px;
            overflow-y: auto;
        }
        
        .user-list:empty::before {
            content: 'Drag users here...';
            display: block;
            text-align: center;
            padding: 60px 20px;
            color: #adb5bd;
            font-size: 14px;
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 12px;
            color: white;
        }
        
        .group-header {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark, #0056b3) 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 8px 8px 0 0;
            margin: -1px -1px 0 -1px;
        }
        
        .unassigned-card {
            background-color: #fff3cd;
            border-color: #ffc107;
        }
        
        .badge-count {
            font-size: 11px;
            padding: 3px 8px;
        }
    </style>
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">User Group Management</h4>
                    <p class="text-muted mb-0">Organize users into groups by dragging and dropping</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGroupModal">
                    <i class="bi bi-plus-lg me-1"></i>Add New Group
                </button>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Unassigned Users -->
        <div class="col-12 col-lg-4">
            <div class="card group-card unassigned-card">
                <div class="group-header" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0"><i class="bi bi-inbox me-2"></i>Unassigned Users</h5>
                            <small class="opacity-75">Users without group</small>
                        </div>
                        <span class="badge bg-white text-dark badge-count" id="unassignedCount">0</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="user-list" id="unassignedUsers" data-group-id="0">
                        @foreach($unassignedUsers ?? [] as $user)
                            <div class="user-item p-3 border-bottom" data-user-id="{{ $user->id }}" data-is-leader="0">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-2" style="background-color: {{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }};">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                    <span class="badge bg-secondary-subtle text-secondary">{{ ucfirst($user->role) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Groups -->
        @foreach($groups ?? [] as $group)
            <div class="col-12 col-lg-4">
                <div class="card group-card">
                    <div class="group-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>{{ $group->name }}</h5>
                                @if($group->description)
                                    <small class="opacity-75">{{ $group->description }}</small>
                                @endif
                            </div>
                            <div class="d-flex gap-2">
                                <span class="badge bg-white text-primary badge-count" id="count-{{ $group->id }}">0</span>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="editGroup({{ $group->id }}, '{{ $group->name }}', '{{ $group->description ?? '' }}')">
                                            <i class="bi bi-pencil me-2"></i>Edit
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteGroup({{ $group->id }}, '{{ $group->name }}')">
                                            <i class="bi bi-trash me-2"></i>Delete
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="user-list" id="group-{{ $group->id }}" data-group-id="{{ $group->id }}">
                            @php
                                $users = $groupUsers[$group->id] ?? [];
                                $leaderId = $groupLeaders[$group->id] ?? null;
                            @endphp
                            @foreach($users as $user)
                                @php
                                    $isLeader = $leaderId == $user->id;
                                    $itemClass = $isLeader ? 'user-item leader' : 'user-item member';
                                @endphp
                                <div class="{{ $itemClass }} p-3 border-bottom" data-user-id="{{ $user->id }}" data-is-leader="{{ $isLeader ? '1' : '0' }}">
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-2" style="background-color: {{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }};">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-semibold">{{ $user->name }}</span>
                                                @if($isLeader)
                                                    <span class="leader-badge">
                                                        <i class="bi bi-star-fill"></i> LEADER
                                                    </span>
                                                @endif
                                            </div>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-secondary-subtle text-secondary">{{ ucfirst($user->role) }}</span>
                                            @if(!$isLeader)
                                                <button class="btn btn-sm btn-outline-warning set-leader-btn" onclick="setAsLeader({{ $user->id }}, {{ $group->id }}, '{{ $user->name }}')" title="Set as leader">
                                                    <i class="bi bi-star"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-outline-secondary set-leader-btn" onclick="removeLeader({{ $user->id }}, {{ $group->id }})" title="Remove as leader">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Add Group Modal -->
    <div class="modal fade" id="addGroupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Add New Group
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addGroupForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Group Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="group_name" required placeholder="e.g. Sales Team">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="group_desc" rows="3" placeholder="Optional group description"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveGroupBtn">
                        <i class="bi bi-check-lg me-1"></i>Create Group
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Group Modal -->
    <div class="modal fade" id="editGroupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil me-2"></i>Edit Group
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editGroupForm">
                        @csrf
                        <input type="hidden" name="group_id" id="editGroupId">
                        <div class="mb-3">
                            <label class="form-label">Group Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="group_name" id="editGroupName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="group_desc" id="editGroupDesc" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="updateGroupBtn">
                        <i class="bi bi-check-lg me-1"></i>Update Group
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Sortable on all user lists
    const userLists = document.querySelectorAll('.user-list');
    
    userLists.forEach(list => {
        new Sortable(list, {
            group: 'users', // Allow dragging between lists
            animation: 200,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            handle: '.user-item',
            onEnd: function(evt) {
                const userId = evt.item.dataset.userId;
                const newGroupId = evt.to.dataset.groupId;
                const wasLeader = evt.item.dataset.isLeader === '1';
                
                // Reset leader status when moving to different group or to unassigned
                if (newGroupId !== evt.from.dataset.groupId) {
                    evt.item.dataset.isLeader = '0';
                    evt.item.classList.remove('leader');
                    evt.item.classList.add('member');
                    
                    // Remove leader badge if exists
                    const leaderBadge = evt.item.querySelector('.leader-badge');
                    if (leaderBadge) {
                        leaderBadge.remove();
                    }
                    
                    // Update button to "set as leader"
                    updateLeaderButton(evt.item, false);
                }
                
                // Remove member class if in unassigned
                if (newGroupId === '0') {
                    evt.item.classList.remove('member', 'leader');
                }
                
                // Save to backend
                saveUserGroupAssignment(userId, newGroupId, wasLeader);
                
                // Update counts
                updateCounts();
                
                // Re-sort: leaders first, then members
                if (newGroupId !== '0') {
                    sortUsersByHierarchy(evt.to);
                }
            }
        });
    });
    
    // Update counts on page load
    updateCounts();
    
    // Function to update user counts
    function updateCounts() {
        userLists.forEach(list => {
            const count = list.querySelectorAll('.user-item').length;
            const groupId = list.dataset.groupId;
            
            if (groupId === '0') {
                document.getElementById('unassignedCount').textContent = count;
            } else {
                const countBadge = document.getElementById(`count-${groupId}`);
                if (countBadge) {
                    countBadge.textContent = count;
                }
            }
        });
    }
    
    // Function to save user-group assignment
    function saveUserGroupAssignment(userId, groupId) {
        fetch('/users/assign-group', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                user_id: userId,
                group_id: groupId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('success', data.message || 'User assigned successfully');
            } else {
                showNotification('error', data.message || 'Failed to assign user');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'An error occurred while assigning user');
        });
    }
    
    // Add Group
    document.getElementById('saveGroupBtn')?.addEventListener('click', function() {
        const form = document.getElementById('addGroupForm');
        const formData = new FormData(form);
        
        const groupName = formData.get('group_name');
        if (!groupName || groupName.trim() === '') {
            showNotification('error', 'Group name is required');
            return;
        }
        
        const submitBtn = this;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Creating...';
        
        fetch('/users/master/group', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(response => {
            if (response.redirected) {
                showNotification('success', 'Group created successfully');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                return response.json();
            }
        })
        .then(data => {
            if (data && data.success) {
                showNotification('success', data.message || 'Group created successfully');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Failed to create group');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Create Group';
        });
    });
    
    // Edit Group
    window.editGroup = function(groupId, groupName, groupDesc) {
        document.getElementById('editGroupId').value = groupId;
        document.getElementById('editGroupName').value = groupName;
        document.getElementById('editGroupDesc').value = groupDesc || '';
        
        const modal = new bootstrap.Modal(document.getElementById('editGroupModal'));
        modal.show();
    };
    
    document.getElementById('updateGroupBtn')?.addEventListener('click', function() {
        const form = document.getElementById('editGroupForm');
        const formData = new FormData(form);
        
        const groupName = formData.get('group_name');
        if (!groupName || groupName.trim() === '') {
            showNotification('error', 'Group name is required');
            return;
        }
        
        const submitBtn = this;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Updating...';
        
        fetch('/users/group/update', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('success', data.message || 'Group updated successfully');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification('error', data.message || 'Failed to update group');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Failed to update group');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Update Group';
        });
    });
    
    // Delete Group
    window.deleteGroup = function(groupId, groupName) {
        if (!confirm(`Are you sure you want to delete "${groupName}"? Users in this group will be unassigned.`)) {
            return;
        }
        
        fetch('/users/group/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ group_id: groupId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('success', data.message || 'Group deleted successfully');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification('error', data.message || 'Failed to delete group');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Failed to delete group');
        });
    };
    
    // Sort users by hierarchy (leaders first)
    function sortUsersByHierarchy(listElement) {
        const items = Array.from(listElement.querySelectorAll('.user-item'));
        items.sort((a, b) => {
            const aIsLeader = a.dataset.isLeader === '1';
            const bIsLeader = b.dataset.isLeader === '1';
            if (aIsLeader && !bIsLeader) return -1;
            if (!aIsLeader && bIsLeader) return 1;
            return 0;
        });
        
        items.forEach(item => listElement.appendChild(item));
    }
    
    // Update leader button UI
    function updateLeaderButton(userItem, isLeader) {
        const buttonsContainer = userItem.querySelector('.d-flex.gap-2:last-child');
        if (!buttonsContainer) return;
        
        const setLeaderBtn = buttonsContainer.querySelector('.set-leader-btn');
        if (!setLeaderBtn) return;
        
        if (isLeader) {
            setLeaderBtn.className = 'btn btn-sm btn-outline-secondary set-leader-btn';
            setLeaderBtn.innerHTML = '<i class="bi bi-x-lg"></i>';
            setLeaderBtn.title = 'Remove as leader';
        } else {
            setLeaderBtn.className = 'btn btn-sm btn-outline-warning set-leader-btn';
            setLeaderBtn.innerHTML = '<i class="bi bi-star"></i>';
            setLeaderBtn.title = 'Set as leader';
        }
    }
    
    // Set user as leader
    window.setAsLeader = function(userId, groupId, userName) {
        if (!confirm(`Set "${userName}" as group leader?`)) {
            return;
        }
        
        fetch('/users/group/set-leader', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                user_id: userId,
                group_id: groupId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('success', data.message || 'Leader set successfully');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification('error', data.message || 'Failed to set leader');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'An error occurred while setting leader');
        });
    };
    
    // Remove user as leader
    window.removeLeader = function(userId, groupId) {
        if (!confirm('Remove this user as group leader?')) {
            return;
        }
        
        fetch('/users/group/remove-leader', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                user_id: userId,
                group_id: groupId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('success', data.message || 'Leader removed successfully');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification('error', data.message || 'Failed to remove leader');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'An error occurred while removing leader');
        });
    };
    
    // Notification function
    function showNotification(type, message) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }
});
</script>
@endsection

