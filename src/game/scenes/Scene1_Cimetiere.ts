import { Scene } from 'phaser';
import { DialogManager } from '../systems/DialogManager';
import { DialogUI } from '../ui/DialogUI';
import { InventoryUI } from '../ui/InventoryUI';
import { UrgencyGauge } from '../ui/UrgencyGauge';

export class Scene1_Cimetiere extends Scene {
    private dialogManager: DialogManager;
    private dialogUI: DialogUI;
    private inventoryUI: InventoryUI;
    private urgencyGauge: UrgencyGauge;

    private hasUSB: boolean = false;

    constructor() {
        super('Scene1_Cimetiere');
    }

    create() {
        const { width, height } = this.scale;

        // Background
        this.add.image(width / 2, height / 2, 'bg_cimetiere').setDisplaySize(width, height);

        // Core Systems
        this.urgencyGauge = new UrgencyGauge(this);
        this.inventoryUI = new InventoryUI(this);

        this.dialogManager = new DialogManager(this);
        this.dialogManager.loadDialogs('dialogs');
        this.dialogUI = new DialogUI(this);

        this.dialogManager.on('dialog-update', (node: any) => {
            this.dialogUI.showDialog(node);
        });

        this.dialogManager.on('dialog-end', () => {
            this.dialogUI.hideDialog();
            if (this.registry.get('scene1_complete')) {
                this.scene.start('Scene2_Atelier');
            }
        });

        // Interactables
        this.createInteractables(width, height);

        // Start Intro
        this.dialogManager.startDialog('scene1_intro');

        // Input handling for dialog
        this.input.on('pointerdown', (_pointer: any, gameObjects: any[]) => {
            if (this.dialogUI.visible) {
                if (this.dialogUI.isTyping) {
                    this.dialogUI.completeTyping();
                } else {
                    this.dialogManager.nextDialog();
                }
            }
        });

        // Inventory selection listener
        this.events.on('inventory-item-selected', (key: string) => {
            if (key === 'item_usb') {
                // Logic to use item is handled in interactables
                console.log("Selected USB Key");
            }
        });
    }

    private createInteractables(width: number, height: number) {
        // Professeur Sceptique
        const prof = this.add.image(width * 0.2, height * 0.7, 'prof').setScale(0.5).setInteractive({ useHandCursor: true });
        prof.on('pointerdown', () => {
            if (!this.dialogUI.visible) this.dialogManager.startDialog('scene1_prof');
        });

        // Cupboard (Puzzle)
        const cupboard = this.add.rectangle(width * 0.8, height * 0.6, 100, 200, 0x663300).setInteractive({ useHandCursor: true });
        this.add.text(width * 0.8, height * 0.6, 'Armoire', { color: '#fff' }).setOrigin(0.5);

        cupboard.on('pointerdown', () => {
            if (!this.hasUSB && !this.dialogUI.visible) {
                // Simulate puzzle resolution
                this.hasUSB = true;
                this.inventoryUI.addItem('item_usb', 'item_usb');
                // Feedback
                const text = this.add.text(width * 0.8, height * 0.4, 'Clé USB trouvée !', { fontSize: '32px', color: '#00ff00', backgroundColor: '#000' }).setOrigin(0.5);
                this.tweens.add({ targets: text, y: height * 0.3, alpha: 0, duration: 2000, onComplete: () => text.destroy() });
            }
        });

        // Dying PC
        const pc = this.add.rectangle(width * 0.5, height * 0.7, 150, 150, 0x555555).setInteractive({ useHandCursor: true });
        this.add.text(width * 0.5, height * 0.7, 'Vieux PC', { color: '#fff' }).setOrigin(0.5);

        pc.on('pointerdown', () => {
            if (this.inventoryUI.hasItem('item_usb') && !this.dialogUI.visible) {
                // Use USB
                this.inventoryUI.removeItem('item_usb');
                this.registry.set('scene1_complete', true);

                // Visual effect
                this.cameras.main.flash(1000, 0, 255, 0);

                // Success Dialog
                this.dialogManager.startDialog('scene1_wiwi_success');
            } else if (!this.dialogUI.visible) {
                // Hint
                const text = this.add.text(width * 0.5, height * 0.6, 'Il lui faut un OS...', { fontSize: '24px', color: '#ff0000', backgroundColor: '#000' }).setOrigin(0.5);
                this.tweens.add({ targets: text, alpha: 0, duration: 2000, onComplete: () => text.destroy() });
            }
        });
    }
}
