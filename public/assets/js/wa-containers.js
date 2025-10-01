document.addEventListener('DOMContentLoaded', function(){
    let containers = [];
    let allGroups = [];
    let allUsers = [];
    let allTemplates = [];
    let draggedElement = null;
    let draggedData = null;
    let containerIdCounter = 1;

    const canvasArea = document.getElementById('canvasArea');
    const addContainerBtn = document.getElementById('addContainerBtn');
    const autoAlignBtn = document.getElementById('autoAlignBtn');
    const saveAllBtn = document.getElementById('saveAllBtn');
    const groupsList = document.getElementById('groupsList');
    const usersList = document.getElementById('usersList');
    const templatesList = document.getElementById('templatesList');
    const searchRecipients = document.getElementById('searchRecipients');
    const searchTemplates = document.getElementById('searchTemplates');

    function showToast(message, type = 'success') {
        const toastContainer = document.getElementById('toast-container') || createToastContainer();
        const toastId = 'toast-' + Date.now();
        const bgColor = type === 'success' ? 'bg-success' : 'bg-danger';
        const icon = type === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle';
        const toastHtml = `<div class="toast align-items-center text-white ${bgColor} border-0" id="${toastId}"><div class="d-flex"><div class="toast-body"><i class="bi ${icon} me-2"></i>${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>`;
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 3000 });
        toast.show();
        toastElement.addEventListener('hidden.bs.toast', () => toastElement.remove());
    }

    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        return container;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    }

    // Auto Save & Load State
    function saveContainersState() {
        const state = {
            containers: containers,
            containerIdCounter: containerIdCounter
        };
        localStorage.setItem('waContainersState', JSON.stringify(state));
        console.log('✅ Containers saved:', containers.length);
    }

    function loadContainersState() {
        const saved = localStorage.getItem('waContainersState');
        console.log('Loading saved state:', saved ? 'Found' : 'Not found');
        if (saved) {
            try {
                const state = JSON.parse(saved);
                containers = state.containers || [];
                containerIdCounter = state.containerIdCounter || 1;
                
                console.log('📦 Restoring', containers.length, 'containers');
                
                if (containers.length > 0) {
                    canvasArea.querySelector('.text-center')?.remove();
                    containers.forEach(container => {
                        console.log('Rendering container:', container.id);
                        renderContainer(container);
                    });
                    saveAllBtn.style.display = 'block';
                    autoAlignBtn.style.display = 'block';
                    console.log('✅ Containers restored successfully');
                }
            } catch (e) {
                console.error('❌ Failed to load containers state:', e);
            }
        }
    }

    function loadData() {
        fetch('/api/wa/audience-options').then(r => r.json()).then(data => {
            allGroups = data.groups || [];
            allUsers = data.users || [];
            allTemplates = (data.templates || []).filter(t => t.type === 'whatsapp' && t.status === 'active');
            renderGroups();
            renderUsers();
            renderTemplates();
            
            // Restore containers after data loaded
            loadContainersState();
        });
    }

    function renderGroups(filter = '') {
        const filtered = allGroups.filter(g => g.name.toLowerCase().includes(filter.toLowerCase()));
        groupsList.innerHTML = filtered.map(g => `
            <div class="source-item" draggable="true" data-type="group" data-id="${g.id}" data-name="${escapeHtml(g.name)}">
                <i class="bi bi-collection me-1"></i>${escapeHtml(g.name)}
            </div>
        `).join('') || '<div class="text-muted text-center p-3 small">No groups</div>';
        attachSourceDrag();
    }

    function renderUsers(filter = '') {
        const filtered = allUsers.filter(u => 
            u.name.toLowerCase().includes(filter.toLowerCase()) || 
            u.email.toLowerCase().includes(filter.toLowerCase())
        );
        usersList.innerHTML = filtered.map(u => `
            <div class="source-item" draggable="true" data-type="user" data-id="${u.id}" data-name="${escapeHtml(u.name)}" data-email="${escapeHtml(u.email)}">
                <i class="bi bi-person me-1"></i>${escapeHtml(u.name)}<br><small class="text-muted">${escapeHtml(u.email)}</small>
            </div>
        `).join('') || '<div class="text-muted text-center p-3 small">No users</div>';
        attachSourceDrag();
    }

    function renderTemplates(filter = '') {
        const filtered = allTemplates.filter(t => t.name.toLowerCase().includes(filter.toLowerCase()));
        templatesList.innerHTML = filtered.map(t => `
            <div class="source-item template-source-item" draggable="true" data-type="template" data-id="${t.id}" data-name="${escapeHtml(t.name)}">
                <i class="bi bi-whatsapp me-1"></i><strong>${escapeHtml(t.name)}</strong>
            </div>
        `).join('') || '<div class="text-muted text-center p-3 small">No templates</div>';
        attachSourceDrag();
    }

    function attachSourceDrag() {
        document.querySelectorAll('.source-item').forEach(item => {
            item.addEventListener('dragstart', (e) => {
                item.classList.add('dragging');
                draggedData = {
                    type: item.dataset.type,
                    id: parseInt(item.dataset.id),
                    name: item.dataset.name,
                    email: item.dataset.email
                };
                e.dataTransfer.effectAllowed = 'copy';
            });
            item.addEventListener('dragend', () => {
                item.classList.remove('dragging');
                draggedData = null;
            });
        });
    }

    function showContainerNameModal() {
        const modal = new bootstrap.Modal(document.getElementById('containerNameModal'));
        const input = document.getElementById('containerNameInput');
        input.value = '';
        modal.show();
        
        // Focus on input after modal shown
        document.getElementById('containerNameModal').addEventListener('shown.bs.modal', function() {
            input.focus();
        }, { once: true });
    }

    function addContainer(name = null) {
        const containerName = name || `Container #${containerIdCounter}`;
        const container = {
            id: containerIdCounter++,
            name: containerName,
            x: 100 + (containers.length * 30),
            y: 50 + (containers.length * 30),
            template: null,
            recipients: []
        };
        containers.push(container);
        renderContainer(container);
        saveAllBtn.style.display = 'block';
        document.getElementById('autoAlignBtn').style.display = 'block';
        canvasArea.querySelector('.text-center')?.remove();
        saveContainersState();
        showToast(`Container "${containerName}" created!`);
    }

    function autoAlignContainers() {
        if (containers.length === 0) return;
        
        const canvasRect = canvasArea.getBoundingClientRect();
        const canvasWidth = canvasArea.clientWidth;
        const canvasHeight = canvasArea.clientHeight;
        const containerWidth = 280;
        const containerHeight = 350; // Approximate height
        const gap = 20;
        const padding = 20;
        
        // Calculate grid
        const cols = Math.floor((canvasWidth - padding * 2 + gap) / (containerWidth + gap));
        const maxCols = Math.max(1, Math.min(3, cols)); // Max 3 columns
        
        containers.forEach((container, index) => {
            const col = index % maxCols;
            const row = Math.floor(index / maxCols);
            
            container.x = padding + (col * (containerWidth + gap));
            container.y = padding + (row * (containerHeight + gap));
            
            updateContainer(container);
        });
        
        saveContainersState();
        showToast('✨ Containers aligned successfully!');
    }

    function renderContainer(container) {
        const containerEl = document.createElement('div');
        containerEl.className = 'notification-container';
        containerEl.id = `container-${container.id}`;
        containerEl.style.left = container.x + 'px';
        containerEl.style.top = container.y + 'px';
        
        const containerName = container.name || `Container #${container.id}`;
        
        containerEl.innerHTML = `
            <div class="container-header" data-container-id="${container.id}">
                <span><i class="bi bi-box me-2"></i>${escapeHtml(containerName)}</span>
                <button class="btn btn-sm btn-danger" style="padding:2px 6px;" onclick="deleteContainer(${container.id})">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="container-body">
                <div class="template-slot ${container.template ? 'filled' : ''}" data-container-id="${container.id}" data-slot="template">
                    ${container.template 
                        ? `<div class="d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-whatsapp me-2"></i><strong>${escapeHtml(container.template.name)}</strong></span>
                            <button class="btn btn-sm btn-outline-danger" style="padding:2px 6px;" onclick="deleteTemplate(${container.id})" title="Remove template">
                                <i class="bi bi-x-lg"></i>
                            </button>
                           </div>` 
                        : '<small class="text-muted"><i class="bi bi-file-earmark-text"></i><br>Drop Template Here</small>'}
                </div>
                <div class="recipients-slot" data-container-id="${container.id}" data-slot="recipients">
                    ${container.recipients.length === 0 
                        ? '<small class="text-muted text-center d-block"><i class="bi bi-people"></i><br>Drop Users/Groups Here</small>' 
                        : container.recipients.map((r, idx) => `
                            <div class="recipient-item">
                                <span><i class="bi ${r.type === 'group' ? 'bi-collection' : 'bi-person'} me-1"></i>${escapeHtml(r.name)}</span>
                                <button class="remove-btn" onclick="removeRecipient(${container.id}, ${idx})">×</button>
                            </div>
                        `).join('')}
                </div>
            </div>
        `;
        
        canvasArea.appendChild(containerEl);
        makeDraggable(containerEl, container);
        attachDropZones(containerEl, container);
    }

    function makeDraggable(element, container) {
        const header = element.querySelector('.container-header');
        let isDragging = false;
        let currentX;
        let currentY;
        let initialX;
        let initialY;

        header.addEventListener('mousedown', (e) => {
            isDragging = true;
            initialX = e.clientX - container.x;
            initialY = e.clientY - container.y;
            element.style.zIndex = '1001';
        });

        document.addEventListener('mousemove', (e) => {
            if (isDragging) {
                e.preventDefault();
                currentX = e.clientX - initialX;
                currentY = e.clientY - initialY;
                element.style.left = currentX + 'px';
                element.style.top = currentY + 'px';
                container.x = currentX;
                container.y = currentY;
            }
        });

        document.addEventListener('mouseup', () => {
            if (isDragging) {
                isDragging = false;
                element.style.zIndex = '';
                saveContainersState(); // Auto save position
            }
        });
    }

    function attachDropZones(containerEl, container) {
        const templateSlot = containerEl.querySelector('[data-slot="template"]');
        const recipientsSlot = containerEl.querySelector('[data-slot="recipients"]');

        templateSlot.addEventListener('dragover', (e) => e.preventDefault());
        templateSlot.addEventListener('drop', (e) => {
            e.preventDefault();
            if (draggedData && draggedData.type === 'template') {
                if (container.template) {
                    showToast('Template already set! Delete container to change.', 'error');
                    return;
                }
                container.template = { id: draggedData.id, name: draggedData.name };
                updateContainer(container);
                saveContainersState(); // Auto save
                showToast('Template added!');
            }
        });

        recipientsSlot.addEventListener('dragover', (e) => {
            e.preventDefault();
            recipientsSlot.classList.add('drag-over');
        });
        recipientsSlot.addEventListener('dragleave', () => {
            recipientsSlot.classList.remove('drag-over');
        });
        recipientsSlot.addEventListener('drop', (e) => {
            e.preventDefault();
            recipientsSlot.classList.remove('drag-over');
            if (draggedData && (draggedData.type === 'user' || draggedData.type === 'group')) {
                const exists = container.recipients.some(r => r.type === draggedData.type && r.id === draggedData.id);
                if (exists) {
                    showToast('Already added!', 'error');
                    return;
                }
                container.recipients.push({
                    type: draggedData.type,
                    id: draggedData.id,
                    name: draggedData.name,
                    email: draggedData.email
                });
                updateContainer(container);
                saveContainersState(); // Auto save
                showToast('Recipient added!');
            }
        });
    }

    function updateContainer(container) {
        const containerEl = document.getElementById(`container-${container.id}`);
        if (containerEl) {
            containerEl.remove();
            renderContainer(container);
        }
    }

    window.removeRecipient = function(containerId, recipientIdx) {
        const container = containers.find(c => c.id === containerId);
        if (container) {
            container.recipients.splice(recipientIdx, 1);
            updateContainer(container);
            saveContainersState(); // Auto save
            showToast('Recipient removed');
        }
    };

    window.deleteTemplate = function(containerId) {
        const container = containers.find(c => c.id === containerId);
        if (container && container.template) {
            container.template = null;
            updateContainer(container);
            saveContainersState(); // Auto save
            showToast('Template removed');
        }
    };

    window.deleteContainer = function(containerId) {
        if (!confirm('Delete this container?')) return;
        const idx = containers.findIndex(c => c.id === containerId);
        if (idx !== -1) {
            containers.splice(idx, 1);
            document.getElementById(`container-${containerId}`).remove();
            saveContainersState(); // Auto save
            showToast('Container deleted');
            if (containers.length === 0) {
                saveAllBtn.style.display = 'none';
                autoAlignBtn.style.display = 'none';
                canvasArea.innerHTML = '<div class="text-center text-muted" style="padding-top: 20%; font-size: 1.2rem;"><i class="bi bi-plus-circle" style="font-size: 4rem; opacity: 0.3;"></i><p>Click "Add Container" to start</p><p class="small">Drag templates and recipients into containers</p></div>';
            }
        }
    };

    addContainerBtn.addEventListener('click', showContainerNameModal);
    
    // Confirm create container from modal
    document.getElementById('confirmCreateContainer').addEventListener('click', () => {
        const input = document.getElementById('containerNameInput');
        const name = input.value.trim();
        
        if (!name) {
            input.classList.add('is-invalid');
            showToast('Please enter a container name!', 'error');
            return;
        }
        
        input.classList.remove('is-invalid');
        const modal = bootstrap.Modal.getInstance(document.getElementById('containerNameModal'));
        modal.hide();
        addContainer(name);
    });
    
    // Enter key to submit
    document.getElementById('containerNameInput').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            document.getElementById('confirmCreateContainer').click();
        }
    });
    
    autoAlignBtn.addEventListener('click', autoAlignContainers);

    saveAllBtn.addEventListener('click', () => {
        const invalidContainers = containers.filter(c => !c.template || c.recipients.length === 0);
        if (invalidContainers.length > 0) {
            showToast('All containers must have a template and at least one recipient!', 'error');
            return;
        }

        const configurations = containers.map(c => {
            const hasGroups = c.recipients.some(r => r.type === 'group');
            const hasUsers = c.recipients.some(r => r.type === 'user');
            let audience_type = 'users';
            let audience_ids = [];
            if (hasGroups && !hasUsers) {
                audience_type = 'group';
                audience_ids = c.recipients.filter(r => r.type === 'group').map(r => r.id);
            } else {
                audience_ids = c.recipients.filter(r => r.type === 'user').map(r => r.id);
            }
            return {
                audience_type,
                audience_ids,
                template_id: c.template.id
            };
        });

        // Save first configuration (for compatibility)
        const payload = configurations[0];
        fetch('/api/wa/setting/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payload)
        }).then(r => r.json()).then(() => {
            showToast(`✅ Saved ${containers.length} configuration(s) successfully!`);
        }).catch(error => {
            console.error('Save error:', error);
            showToast('Failed to save', 'error');
        });
    });

    searchRecipients.addEventListener('input', (e) => {
        const val = e.target.value;
        renderGroups(val);
        renderUsers(val);
    });

    searchTemplates.addEventListener('input', (e) => {
        renderTemplates(e.target.value);
    });

    loadData();
});
