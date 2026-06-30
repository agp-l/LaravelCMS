document.addEventListener('DOMContentLoaded', function() {
    
    const monthNames = ["Leden", "Únor", "Březen", "Duben", "Květen", "Červen", "Červenec", "Srpen", "Září", "Říjen", "Listopad", "Prosinec"];
    const dayNames = ["Neděle", "Pondělí", "Úterý", "Středa", "Čtvrtek", "Pátek", "Sobota"];

    let currentDate = new Date();
    currentDate.setHours(0,0,0,0);
    let selectedDate = new Date();
    selectedDate.setHours(0,0,0,0);
    
    // BEZPEČNÉ OBNOVENÍ STAVU (zabraňuje chybě NaN)
    const oldState = window.ServerOldState || {};
    if (oldState.date && typeof oldState.date === 'string') {
        let parts = oldState.date.split('-');
        if (parts.length === 3) {
            let parsedOldDate = new Date(parts[0], parts[1] - 1, parts[2]);
            if (!isNaN(parsedOldDate)) {
                currentDate = new Date(parsedOldDate); 
                selectedDate = new Date(parsedOldDate);
            }
        }
    }
    
    let activePricePerHour = 0;
    let activePricePerDay = 0;
    let activePricePerMonth = 0;
    let activePricingModel = 'hourly';
    let activeMaxCapacity = 1;

    const calMonthText = document.getElementById('calMonthText');
    const calDaysContainer = document.getElementById('calDaysContainer');
    const hiddenDateOutput = document.getElementById('selectedDateOutput');
    const hiddenRecurringOutput = document.getElementById('recurringDaysOutput');
    const slotsContainer = document.getElementById('dynamicSlotsContainer');
    const priceDisplay = document.getElementById('calculated-price');
    const calendarStep = document.getElementById('calendar-step');
    const slotsStep = document.getElementById('slots-step');
    const submitBtn = document.getElementById('submit-btn');

    const wrapChildName = document.getElementById('wrap-child-name');
    const inputChildName = document.getElementById('input-child-name');
    const wrapChildInfo = document.getElementById('wrap-child-info');
    const wrapKidsCount = document.getElementById('wrap-kids-count');
    const inputKidsCount = document.getElementById('input-kids-count');
    const wrapSharing = document.getElementById('wrap-sharing');
    const inputSharing = document.getElementById('input-sharing');
    const wrapPricing = document.getElementById('wrap-pricing');
    const inputPricing = document.getElementById('input-pricing');
    const wrapNote = document.getElementById('wrap-note');
    const wrapCustomField = document.getElementById('wrap-custom-field');
    const inputCustomField = document.getElementById('input-custom-field');
    const labelCustomField = document.getElementById('label-custom-field');

    // TVRDÁ POJISTKA PŘI ODESLÁNÍ
// TVRDÁ POJISTKA PŘI ODESLÁNÍ
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            // Bez ohledu na to, co si myslí prohlížeč nebo doplňky, 
            // těsně před odesláním tam to datum natvrdo znovu přepíšeme!
            if (hiddenDateOutput && selectedDate && !isNaN(selectedDate)) {
                hiddenDateOutput.value = formatDateForInput(selectedDate);
            }
        });
    }

    if (document.getElementById('scrollPrev') && calDaysContainer) {
        document.getElementById('scrollPrev').addEventListener('click', function() {
            calDaysContainer.scrollBy({ left: -240, behavior: 'smooth' });
        });
    }

    if (document.getElementById('scrollNext') && calDaysContainer) {
        document.getElementById('scrollNext').addEventListener('click', function() {
            calDaysContainer.scrollBy({ left: 240, behavior: 'smooth' });
        });
    }

    function formatDateForInput(d) {
        if (isNaN(d)) return "";
        return d.getFullYear() + "-" + String(d.getMonth() + 1).padStart(2, '0') + "-" + String(d.getDate()).padStart(2, '0');
    }

    function renderSlots(dateStr, activityId) {
        if (!slotsContainer) return;
        slotsContainer.innerHTML = '<div class="text-center p-4 text-muted"><i class="fa-solid fa-spinner fa-spin"></i> Načítám volné termíny...</div>';
        
        fetch(`/api/reservation/availability?date=${dateStr}&activity_id=${activityId}`)
            .then(response => {
                if (!response.ok) throw new Error('Chyba serveru ' + response.status);
                return response.json();
            })
            .then(data => {
                slotsContainer.innerHTML = '';
                
                if (data.is_blocked || data.slots.length === 0) {
                    slotsContainer.innerHTML = '<div class="alert alert-warning text-center w-100 border-0 shadow-sm">Pro tento den nejsou vypsány žádné volné termíny.</div>';
                    updatePrice();
                    return;
                }

                data.slots.forEach(slotData => {
                    let html = "";
                    let namesHtml = "";
                    if (slotData.children_names && slotData.children_names.length > 0) {
                        let namesList = slotData.children_names.join(', ');
                        namesHtml = `<div class="mt-1" style="font-size: 0.7rem; color: #64748b; line-height: 1.2; font-weight: 500;">${namesList}</div>`;
                    }

                    let isChecked = (oldState.slots && oldState.slots.includes(slotData.slot)) ? 'checked' : '';

                    if (slotData.status === 'FULL') {
                        html = `
                            <div class="col-6">
                                <label class="slot-checkbox-label w-100 disabled">
                                    <input type="checkbox" disabled>
                                    <div class="slot-box">
                                        ${slotData.slot}<br>
                                        <small class="fw-normal text-danger">Obsazeno</small>
                                        ${namesHtml}
                                    </div>
                                </label>
                            </div>`;
                    } else {
                        let subText = slotData.status === 'SHARED' ? `Skupina (${slotData.current_kids}/${slotData.max_capacity})` : 'Volno';
                        html = `
                            <div class="col-6">
                                <label class="slot-checkbox-label w-100">
                                    <input type="checkbox" name="slot[]" value="${slotData.slot}" class="slot-element" data-status="${slotData.status}" ${isChecked}>
                                    <div class="slot-box">
                                        ${slotData.slot}<br>
                                        <small class="fw-normal">${subText}</small>
                                        ${namesHtml}
                                    </div>
                                </label>
                            </div>`;
                    }
                    slotsContainer.insertAdjacentHTML('beforeend', html);
                });

                document.querySelectorAll('.slot-element').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        updatePrice();
                        checkSharingLogic();
                    });
                });
                updatePrice();
                checkSharingLogic();
            })
            .catch(error => {
                slotsContainer.innerHTML = '<div class="alert alert-danger text-center w-100 border-0 shadow-sm">Chyba při komunikaci se serverem. Nelze načíst časy.</div>';
                console.error("API Error:", error);
            });
    }

    function updatePrice() {
        if (!priceDisplay) return;
        const checkedCount = document.querySelectorAll('.slot-element:checked').length;
        const pricingModel = inputPricing ? inputPricing.value : activePricingModel;
        let totalPrice = 0;

        if (pricingModel === 'monthly') {
            totalPrice = activePricePerMonth;
        } else if (pricingModel === 'daily') {
            totalPrice = activePricePerDay;
        } else {
            totalPrice = checkedCount * activePricePerHour;
        }

        priceDisplay.innerText = totalPrice.toLocaleString('cs-CZ') + " Kč";
    }

    if (inputPricing) {
        inputPricing.addEventListener('change', function() {
            updatePrice();
            visualizeMonthlySelection(); 
        });
    }

    function checkSharingLogic() {
        const activeRadio = document.querySelector('.activity-radio:checked');
        if (!activeRadio || !inputSharing || !wrapSharing) return;

        const bookingMode = activeRadio.getAttribute('data-booking-mode');
        let hasSharedSlot = false;
        
        document.querySelectorAll('.slot-element:checked').forEach(cb => {
            if (cb.getAttribute('data-status') === 'SHARED') hasSharedSlot = true;
        });

        inputSharing.innerHTML = '';

        if (hasSharedSlot) {
            wrapSharing.classList.remove('d-none');
            inputSharing.insertAdjacentHTML('beforeend', '<option value="Sdílený čas" selected>Otevřená parta (Někdo už je přihlášen)</option>');
        } else {
            if (bookingMode === 'both') {
                wrapSharing.classList.remove('d-none');
                inputSharing.insertAdjacentHTML('beforeend', '<option value="Individuální čas">Individuálně / soukromě</option>');
                inputSharing.insertAdjacentHTML('beforeend', '<option value="Sdílený čas">Otevřená parta</option>');
            } else if (bookingMode === 'individual') {
                wrapSharing.classList.add('d-none');
                inputSharing.insertAdjacentHTML('beforeend', '<option value="Individuální čas" selected>Individuálně / soukromě</option>');
            } else if (bookingMode === 'shared') {
                wrapSharing.classList.add('d-none');
                inputSharing.insertAdjacentHTML('beforeend', '<option value="Sdílený čas" selected>Otevřená parta</option>');
            }
            
            if (oldState.sharing && !wrapSharing.classList.contains('d-none')) {
                let savedSharing = oldState.sharing;
                if(savedSharing.includes("Někdo už je přihlášen")) savedSharing = "Sdílený čas";
                Array.from(inputSharing.options).forEach(opt => {
                    if (opt.value === savedSharing) opt.selected = true;
                });
            }
        }
    }

    function updateFormVisibility(radio) {
        const showChildName = radio.getAttribute('data-show-child-name') === '1';
        const showKidsCount = radio.getAttribute('data-show-kids-count') === '1';
        const showChildInfo = radio.getAttribute('data-show-child-info') === '1';
        const showNote = radio.getAttribute('data-show-note') === '1';
        const customLabel = radio.getAttribute('data-custom-label');
        const customRequired = radio.getAttribute('data-custom-required') === '1';
        
        activePricingModel = radio.getAttribute('data-pricing-model'); 
        activeMaxCapacity = parseInt(radio.getAttribute('data-max-capacity')) || 1;

        if (wrapChildName && inputChildName) {
            if (showChildName) { wrapChildName.classList.remove('d-none'); inputChildName.required = true; } 
            else { wrapChildName.classList.add('d-none'); inputChildName.required = false; }
        }

        if (wrapChildInfo) {
            if (showChildInfo) { wrapChildInfo.classList.remove('d-none'); } 
            else { wrapChildInfo.classList.add('d-none'); }
        }

        if (wrapKidsCount && inputKidsCount) {
            if (showKidsCount && activeMaxCapacity > 1) {
                wrapKidsCount.classList.remove('d-none');
                inputKidsCount.innerHTML = '';
                for (let i = 1; i <= Math.min(activeMaxCapacity, 5); i++) {
                    let text = i === 1 ? '1 dítě' : (i >= 2 && i <= 4 ? `${i} děti` : `${i} dětí`);
                    inputKidsCount.insertAdjacentHTML('beforeend', `<option value="${i}">${text}</option>`);
                }
                if (oldState.kidsCount) inputKidsCount.value = oldState.kidsCount;
            } else {
                wrapKidsCount.classList.add('d-none');
                inputKidsCount.innerHTML = '<option value="1" selected>1 dítě</option>';
            }
        }

        checkSharingLogic();

        if (inputPricing && wrapPricing) {
            inputPricing.innerHTML = '';
            if (activePricingModel === 'monthly') {
                inputPricing.insertAdjacentHTML('beforeend', '<option value="monthly" selected>Měsíční paušál</option>');
                wrapPricing.classList.add('d-none'); 
            } else if (activePricingModel === 'daily') {
                inputPricing.insertAdjacentHTML('beforeend', '<option value="daily" selected>Denní paušál</option>');
                wrapPricing.classList.add('d-none');
            } else {
                wrapPricing.classList.remove('d-none');
                inputPricing.insertAdjacentHTML('beforeend', '<option value="hourly" selected>Cena od hodiny</option>');
                if(activePricePerDay > 0) inputPricing.insertAdjacentHTML('beforeend', '<option value="daily">Celodenní paušál</option>');
                if(activePricePerMonth > 0) inputPricing.insertAdjacentHTML('beforeend', '<option value="monthly">Měsíční paušál</option>');
            }
            
            if (oldState.pricing && !wrapPricing.classList.contains('d-none')) {
                inputPricing.value = oldState.pricing;
            }
        }

        if (wrapNote) {
            if (showNote) wrapNote.classList.remove('d-none');
            else wrapNote.classList.add('d-none');
        }

        if (wrapCustomField && inputCustomField && labelCustomField) {
            if (customLabel && customLabel.trim() !== '') {
                wrapCustomField.classList.remove('d-none');
                labelCustomField.innerText = customLabel + (customRequired ? '' : ' (nepovinné)');
                inputCustomField.required = customRequired;
            } else {
                wrapCustomField.classList.add('d-none');
                inputCustomField.required = false;
            }
        }
    }

    function visualizeMonthlySelection() {
        const activeRadio = document.querySelector('.activity-radio:checked');
        if (!activeRadio || !calDaysContainer) return;

        let isMonthly = (inputPricing && inputPricing.value === 'monthly');
        let monthlyMode = activeRadio.getAttribute('data-monthly-mode') || 'all_days';
        let selectedMonth = selectedDate.getMonth();
        let selectedDow = selectedDate.getDay();
        let recurringDays = [];

        document.querySelectorAll('#calDaysContainer .day-btn').forEach(b => b.classList.remove('active'));

        if (isMonthly) {
            document.querySelectorAll('#calDaysContainer .day-btn').forEach(b => {
                let loopDateStr = b.getAttribute('data-date');
                if (!loopDateStr) return;
                
                let parts = loopDateStr.split('-');
                let loopDate = new Date(parts[0], parts[1] - 1, parts[2]);
                
                if (loopDate.getMonth() === selectedMonth && !b.classList.contains('disabled')) {
                    if (monthlyMode === 'all_days') {
                        b.classList.add('active');
                        if(!recurringDays.includes(loopDate.getDay())) recurringDays.push(loopDate.getDay());
                    } else if (monthlyMode === 'single_day' && loopDate.getDay() === selectedDow) {
                        b.classList.add('active');
                        if(!recurringDays.includes(loopDate.getDay())) recurringDays.push(loopDate.getDay());
                    }
                }
            });
            if (hiddenRecurringOutput) hiddenRecurringOutput.value = JSON.stringify(recurringDays);
        } else {
            document.querySelectorAll('#calDaysContainer .day-btn').forEach(b => {
                if (b.getAttribute('data-date') === formatDateForInput(selectedDate)) {
                    b.classList.add('active');
                }
            });
            if (hiddenRecurringOutput) hiddenRecurringOutput.value = '';
        }
    }

    function renderCalendar() {
        const activeRadio = document.querySelector('.activity-radio:checked');
        if (!activeRadio) return;

        updateFormVisibility(activeRadio);

        let rawDays = activeRadio.getAttribute('data-days');
        let allowedDays = [];
        if (rawDays) {
            try { allowedDays = JSON.parse(rawDays).map(Number); } catch (e) { }
        }
        
        const themeColor = activeRadio.getAttribute('data-color') || '#059669';
        
        activePricePerHour = parseFloat(activeRadio.getAttribute('data-price')) || 0;
        activePricePerDay = parseFloat(activeRadio.getAttribute('data-price-day')) || 0;
        activePricePerMonth = parseFloat(activeRadio.getAttribute('data-price-month')) || 0;

        if (calendarStep) calendarStep.style.setProperty('--theme-color', themeColor);
        if (slotsStep) slotsStep.style.setProperty('--theme-color', themeColor);
        if (submitBtn) submitBtn.style.background = themeColor;

        if (!calDaysContainer) return;
        calDaysContainer.innerHTML = '';
        let firstAvailableDateSet = false;

        for (let i = 0; i < 60; i++) {
            let d = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate() + i);

            let dayOfWeek = d.getDay();
            let isDayAllowed = allowedDays.includes(dayOfWeek);
            let isSelected = formatDateForInput(selectedDate) === formatDateForInput(d);
            let isToday = formatDateForInput(new Date()) === formatDateForInput(d);
            let dayLabel = isToday && i === 0 ? "Dnes" : dayNames[dayOfWeek];

            let btn = document.createElement('div');
            btn.setAttribute('data-date', formatDateForInput(d));
            
            if (isDayAllowed) {
                btn.className = 'day-btn available';
                btn.innerHTML = `<span class="d-block small text-muted">${dayLabel}</span><strong class="d-block fs-5">${d.getDate()}. ${d.getMonth()+1}.</strong>`;
                
                let hiddenDateVal = hiddenDateOutput ? hiddenDateOutput.value : '';
                if (!firstAvailableDateSet && (!isSelected || hiddenDateVal === '')) {
                    if (!oldState.date || oldState.date === '') {
                        selectedDate = new Date(d);
                    }
                    firstAvailableDateSet = true;
                }

                // Bezpečné volání bez nutnosti znovu převádět text na datum
                btn.onclick = (function(capturedDate) {
                    return function() {
                        selectedDate = new Date(capturedDate);
                        let dateStr = formatDateForInput(selectedDate);
                        if (hiddenDateOutput) hiddenDateOutput.value = dateStr;
                        if (calMonthText) calMonthText.innerHTML = `<i class="fa-regular fa-calendar me-2"></i> ${monthNames[selectedDate.getMonth()]} ${selectedDate.getFullYear()}`;
                        
                        visualizeMonthlySelection(); 
                        
                        oldState.slots = [];
                        renderSlots(dateStr, activeRadio.value);
                    };
                })(d);
            } else {
                btn.className = 'day-btn disabled';
                btn.innerHTML = `<span class="d-block small text-muted">${dayLabel}</span><strong class="d-block text-muted">${d.getDate()}. ${d.getMonth()+1}.</strong>`;
            }
            calDaysContainer.appendChild(btn);
        }

        let finalDateStr = formatDateForInput(selectedDate);
        if (calMonthText) calMonthText.innerHTML = `<i class="fa-regular fa-calendar me-2"></i> ${monthNames[selectedDate.getMonth()]} ${selectedDate.getFullYear()}`;
        if (hiddenDateOutput) hiddenDateOutput.value = finalDateStr;
        
        visualizeMonthlySelection();
        renderSlots(finalDateStr, activeRadio.value);
        updatePrice();
    }

    // PŘEPÍNÁNÍ AKTIVIT
    document.querySelectorAll('.activity-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.activity-card').forEach(card => card.classList.remove('active'));
            this.parentNode.querySelector('.activity-card').classList.add('active');
            
            // Uživatelský reset POUZE, pokud sám klikne na jinou aktivitu
            oldState.date = '';
            oldState.slots = [];
            currentDate = new Date();
            
            if (hiddenDateOutput) hiddenDateOutput.value = '';
            if (hiddenRecurringOutput) hiddenRecurringOutput.value = '';
            
            renderCalendar();
            if (calDaysContainer) calDaysContainer.scrollLeft = 0;
        });
    });

    // POČÁTEČNÍ NAČTENÍ (TADY BYLA TA CHYBA, UŽ SE NESIMULUJE CHANGE EVENT!)
    renderCalendar();
});