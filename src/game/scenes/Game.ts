import { Scene } from 'phaser';
import { DialogManager } from '../systems/DialogManager';
import { DialogUI } from '../ui/DialogUI';

export class Game extends Scene {
    camera: Phaser.Cameras.Scene2D.Camera;
    background: Phaser.GameObjects.Image;
    dialogManager: DialogManager;
    dialogUI: DialogUI;

    constructor() {
        super('Game');
    }

    create() {
        this.camera = this.cameras.main;
        this.camera.setBackgroundColor(0x00ff00);

        this.background = this.add.image(512, 384, 'background');
        this.background.setAlpha(1);

        // Initialize Dialog System
        this.dialogManager = new DialogManager(this);
        this.dialogManager.loadDialogs('dialogs');

        this.dialogUI = new DialogUI(this);

        // Event listeners
        this.dialogManager.on('dialog-start', () => {
            console.log('Dialog started');
        });

        this.dialogManager.on('dialog-update', (node: any) => {
            this.dialogUI.showDialog(node);
        });

        this.dialogManager.on('dialog-end', () => {
            this.dialogUI.hideDialog();
            console.log('Dialog ended');
            // Optional: Transition to next state or enable gameplay
        });

        // Start the intro dialog
        this.dialogManager.startDialog('intro');

        // Input handling to advance dialog
        this.input.on('pointerdown', () => {
            if (this.dialogUI.isTyping) {
                this.dialogUI.completeTyping();
            } else {
                this.dialogManager.nextDialog();
            }
        });

        this.input.keyboard?.on('keydown-SPACE', () => {
            if (this.dialogUI.isTyping) {
                this.dialogUI.completeTyping();
            } else {
                this.dialogManager.nextDialog();
            }
        });
    }
}
