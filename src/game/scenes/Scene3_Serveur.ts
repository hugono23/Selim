import { Scene } from 'phaser';
import { DialogManager } from '../systems/DialogManager';
import { DialogUI } from '../ui/DialogUI';
import { InventoryUI } from '../ui/InventoryUI';
import { UrgencyGauge } from '../ui/UrgencyGauge';

export class Scene3_Serveur extends Scene {
    private dialogManager: DialogManager;
    private dialogUI: DialogUI;
    private inventoryUI: InventoryUI;
    private urgencyGauge: UrgencyGauge;

    constructor() {
        super('Scene3_Serveur');
    }

    create() {
        const { width, height } = this.scale;

        // Background
        this.add.image(width / 2, height / 2, 'bg_serveur').setDisplaySize(width, height);

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
            if (this.registry.get('scene3_complete')) {
                // End game or return to menu
                this.scene.start('MainMenu');
            }
        });

        // Interactables
        this.createInteractables(width, height);

        // Start Intro
        this.dialogManager.startDialog('scene3_intro');

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

        // Add Tchap to inventory
        this.inventoryUI.addItem('item_tchap', 'item_tchap');
    }

    private createInteractables(width: number, height: number) {
        // Firewall Boss
        const firewall = this.add.image(width * 0.5, height * 0.4, 'wizart_firewall').setScale(0.8).setInteractive({ useHandCursor: true });

        firewall.on('pointerdown', () => {
            if (this.inventoryUI.hasItem('item_tchap') && !this.dialogUI.visible) {
                // Trigger ending sequence
                this.registry.set('scene3_complete', true);

                // Visual effect
                this.cameras.main.shake(500, 0.01);
                this.cameras.main.flash(1000, 0, 0, 255);

                this.dialogManager.startDialog('scene3_ending');
            }
        });
    }
}
