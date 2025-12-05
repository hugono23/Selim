import { Scene } from 'phaser';
import { DialogManager } from '../systems/DialogManager';
import { DialogUI } from '../ui/DialogUI';

export class Scene0_Intro extends Scene {
    private dialogManager: DialogManager;
    private dialogUI: DialogUI;

    constructor() {
        super('Scene0_Intro');
    }

    create() {
        const { width, height } = this.scale;

        // Simple background (black or reused)
        this.add.rectangle(0, 0, width, height, 0x000000).setOrigin(0);

        // Core Systems
        this.dialogManager = new DialogManager(this);
        this.dialogManager.loadDialogs('dialogs');
        this.dialogUI = new DialogUI(this);

        this.dialogManager.on('dialog-update', (node: any) => {
            this.dialogUI.showDialog(node);
        });

        this.dialogManager.on('dialog-end', () => {
            this.dialogUI.hideDialog();
            // Transition to Scene 1
            this.scene.start('Scene1_Cimetiere');
        });

        // Start Intro Dialog
        this.dialogManager.startDialog('scene0_intro');

        // Input handling for dialog
        this.input.on('pointerdown', () => {
            if (this.dialogUI.visible) {
                if (this.dialogUI.isTyping) {
                    this.dialogUI.completeTyping();
                } else {
                    this.dialogManager.nextDialog();
                }
            }
        });

        this.input.keyboard?.on('keydown-SPACE', () => {
            if (this.dialogUI.visible) {
                if (this.dialogUI.isTyping) {
                    this.dialogUI.completeTyping();
                } else {
                    this.dialogManager.nextDialog();
                }
            }
        });
    }
}
