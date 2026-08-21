document.addEventListener('change', async (e) => {
    if (e.isConverted) return;
    if (e.target.type !== 'file') return;
    
    const files = Array.from(e.target.files || []);
    const hasHeic = files.some(f => f.name.toLowerCase().endsWith('.heic'));
    
    if (hasHeic) {
        e.preventDefault();
        e.stopImmediatePropagation();
        
        if (!window.heic2any) {
            await new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/heic2any@0.0.4/dist/heic2any.min.js';
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }

        const dt = new DataTransfer();
        for (let file of files) {
            if (file.name.toLowerCase().endsWith('.heic')) {
                try {
                    const convertedBlob = await window.heic2any({
                        blob: file,
                        toType: 'image/jpeg',
                        quality: 0.8
                    });
                    const blob = Array.isArray(convertedBlob) ? convertedBlob[0] : convertedBlob;
                    const newFileName = file.name.replace(/\.heic$/i, '.jpg');
                    const newFile = new File([blob], newFileName, { type: 'image/jpeg' });
                    dt.items.add(newFile);
                } catch (err) {
                    console.error("HEIC conversion failed:", err);
                    dt.items.add(file);
                }
            } else {
                dt.items.add(file);
            }
        }
        
        e.target.files = dt.files;
        
        const newEvent = new Event('change', { bubbles: true });
        newEvent.isConverted = true;
        e.target.dispatchEvent(newEvent);
    }
}, true);

document.addEventListener('drop', async (e) => {
    if (e.isConverted) return;
    if (!e.dataTransfer || !e.dataTransfer.files.length) return;
    
    const files = Array.from(e.dataTransfer.files);
    const hasHeic = files.some(f => f.name.toLowerCase().endsWith('.heic'));
    
    if (hasHeic) {
        e.preventDefault();
        e.stopImmediatePropagation();
        
        if (!window.heic2any) {
            await new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/heic2any@0.0.4/dist/heic2any.min.js';
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }

        const dt = new DataTransfer();
        for (let file of files) {
            if (file.name.toLowerCase().endsWith('.heic')) {
                try {
                    const convertedBlob = await window.heic2any({
                        blob: file,
                        toType: 'image/jpeg',
                        quality: 0.8
                    });
                    const blob = Array.isArray(convertedBlob) ? convertedBlob[0] : convertedBlob;
                    const newFileName = file.name.replace(/\.heic$/i, '.jpg');
                    const newFile = new File([blob], newFileName, { type: 'image/jpeg' });
                    dt.items.add(newFile);
                } catch (err) {
                    console.error("HEIC conversion failed:", err);
                    dt.items.add(file);
                }
            } else {
                dt.items.add(file);
            }
        }
        
        const newEvent = new DragEvent('drop', {
            bubbles: true,
            cancelable: true,
            dataTransfer: dt
        });
        newEvent.isConverted = true;
        e.target.dispatchEvent(newEvent);
    }
}, true);
