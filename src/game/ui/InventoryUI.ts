import Phaser from 'phaser';

export class InventoryUI extends Phaser.GameObjects.Container {
    private background: Phaser.GameObjects.Rectangle;
    private items: Phaser.GameObjects.Sprite[] = [];
    private itemKeys: string[] = [];

    constructor(scene: Phaser.Scene) {
        super(scene, 0, 0);
        this.scene = scene;

        this.createBackground();
        this.scene.add.existing(this);
        this.setDepth(90); // Below dialog but above game world

        // Listen for resize to update position
        this.scene.scale.on('resize', this.layout, this);
        this.layout();
    }

    private createBackground() {
        // Dock style background
        this.background = this.scene.add.rectangle(0, 0, 1, 1, 0x333333, 0.8);
        this.background.setStrokeStyle(2, 0x555555);
        this.add(this.background);
    }

    public layout() {
        const { width, height } = this.scene.scale;
        const dockHeight = 80;
        const dockWidth = width * 0.6;

        this.setPosition(width / 2, height - dockHeight / 2 - 10);
        this.background.setSize(dockWidth, dockHeight);

        // Reposition items
        this.updateItemPositions();
    }

    public addItem(key: string, texture: string) {
        if (this.itemKeys.includes(key)) return;

        const item = this.scene.add.sprite(0, 0, texture);
        item.setDisplaySize(64, 64);
        item.setInteractive({ useHandCursor: true });

        // Tooltip or interaction logic could go here
        item.on('pointerdown', () => {
            console.log(`Selected item: ${key}`);
            this.scene.events.emit('inventory-item-selected', key);
        });

        this.add(item);
        this.items.push(item);
        this.itemKeys.push(key);

        this.updateItemPositions();
    }

    public removeItem(key: string) {
        const index = this.itemKeys.indexOf(key);
        if (index > -1) {
            const item = this.items[index];
            item.destroy();
            this.items.splice(index, 1);
            this.itemKeys.splice(index, 1);
            this.updateItemPositions();
        }
    }

    public hasItem(key: string): boolean {
        return this.itemKeys.includes(key);
    }

    private updateItemPositions() {
        const spacing = 80;
        const startX = -(this.items.length - 1) * spacing / 2;

        this.items.forEach((item, index) => {
            item.setPosition(startX + index * spacing, 0);
        });
    }
}
