import { Scene } from 'phaser';
import { DialogManager } from '../systems/DialogManager';
import { DialogUI } from '../ui/DialogUI';
import { InventoryUI } from '../ui/InventoryUI';
import { UrgencyGauge } from '../ui/UrgencyGauge';

export class Scene2_Atelier extends Scene {
    private dialogManager: DialogManager;
    private dialogUI: DialogUI;
    private inventoryUI: InventoryUI;
    private urgencyGauge: UrgencyGauge;

    private pcsRepaired: number = 0;

    constructor() {
        super('Scene2_Atelier');
    }

    create() {
        const { width, height } = this.scale;

        // Reset state
        this.pcsRepaired = 0;

        // Background
        this.add.image(width / 2, height / 2, 'bg_atelier').setDisplaySize(width, height);

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
            if (this.registry.get('scene2_complete')) {
                this.scene.start('Scene3_Serveur');
            }
        });

        // Interactables
        this.createInteractables(width, height);

        // Start Intro
        this.dialogManager.startDialog('scene2_intro');

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

        // Add SSD to inventory for testing/gameplay flow
        this.inventoryUI.addItem('item_ssd', 'item_ssd');
    }

    private createInteractables(width: number, height: number) {
        // Student Geek
        const student = this.add.image(width * 0.3, height * 0.6, 'student').setScale(0.5).setInteractive({ useHandCursor: true });
        student.on('pointerdown', () => {
            if (!this.dialogUI.visible) this.dialogManager.startDialog('scene2_geek_choice');
        });

        // Broken PCs
        const pcPositions = [
            { x: width * 0.5, y: height * 0.7 },
            { x: width * 0.7, y: height * 0.7 },
            { x: width * 0.9, y: height * 0.7 }
        ];

        pcPositions.forEach((pos, index) => {
            const pc = this.add.rectangle(pos.x, pos.y, 100, 100, 0x990000).setInteractive({ useHandCursor: true });
            this.add.text(pos.x, pos.y, `PC ${index + 1}`, { color: '#fff' }).setOrigin(0.5);

            pc.on('pointerdown', () => {
                if (this.inventoryUI.hasItem('item_ssd') && !this.dialogUI.visible) {
                    // Repair logic
                    // In a real game, we'd remove 1 SSD, but here we assume we have a box of them or infinite supply for simplicity
                    // or we just repair one by one. Let's just count repairs.

                    // Visual feedback
                    pc.setFillStyle(0x00ff00);
                    this.add.text(pos.x, pos.y - 60, 'RÉPARÉ !', { color: '#00ff00', fontSize: '20px', backgroundColor: '#000' }).setOrigin(0.5);

                    this.pcsRepaired++;

                    if (this.pcsRepaired >= 3) {
                        this.registry.set('scene2_complete', true);
                        this.dialogManager.startDialog('scene2_success');
                    }
                }
            });
        });
    }
}
